<?php
/*
 * AmigoPet - Modal de Perfil de Animal (para abrir via JS)
 * Agora carrega dados via AJAX de /api/animal.php
 */
?>

<!-- Modal grande para perfil do animal -->
<div class="modal fade" id="animalProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 80vw;">
    <div class="modal-content" style="max-height: 95vh;">
      <div class="modal-header py-2">
        <h5 class="modal-title" id="animalProfileModalLabel">Perfil do Animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body p-0" style="overflow-y: auto; max-height: calc(95vh - 50px);">
        <div id="animalProfileContent"></div>
      </div>
    </div>
  </div>
</div>

<script>
async function openAnimalProfile(petId) {
  if (!petId) return false;
  try {
    // Carrega o conteúdo do perfil via AJAX
    const contentDiv = document.getElementById('animalProfileContent');
    contentDiv.innerHTML = '<div class="p-4 text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    const res = await fetch('/App/View/includes/contents/animal_profile_content.php?id=' + encodeURIComponent(petId));
    if (!res.ok) {
      contentDiv.innerHTML = '<div class="p-4"><div class="alert alert-danger">Erro ao carregar perfil do animal.</div></div>';
      return false;
    }

    const html = await res.text();
    contentDiv.innerHTML = html;

    // Executa os scripts do conteúdo carregado
    const scripts = contentDiv.querySelectorAll('script');
    scripts.forEach(script => {
      const newScript = document.createElement('script');
      newScript.textContent = script.textContent;
      script.parentNode.replaceChild(newScript, script);
    });

    const modalEl = document.getElementById('animalProfileModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    return true;
  } catch (e) {
    const contentDiv = document.getElementById('animalProfileContent');
    contentDiv.innerHTML = '<div class="p-4"><div class="alert alert-danger">Erro ao carregar perfil do animal.</div></div>';
    return false;
  }
}

document.addEventListener('DOMContentLoaded', function() {
    // Handler para abrir modal ao clicar em elementos relacionados ao pet
    document.body.addEventListener('click', function(e) {
      // botões específicos do perfil
      const btn = e.target.closest('.btn-open-profile');
      if (btn) {
        const pid = btn.getAttribute('data-pet-id');
        if (pid) openAnimalProfile(pid);
        e.preventDefault();
        return;
      }

      // qualquer elemento com data-pet-id (imagem, badge, etc.)
      const elWithId = e.target.closest('[data-pet-id], [data-pet-name]');
      if (elWithId && !e.target.closest('a,button')) {
        const pid = elWithId.getAttribute('data-pet-id');
        const pname = elWithId.getAttribute('data-pet-name');
        if (pid && openAnimalProfile(pid)) {
          e.preventDefault();
          return;
        }
        if (pname && openAnimalProfile(pname)) {
          e.preventDefault();
          return;
        }
      }

      // fallback: clicar no cartão inteiro
      const card = e.target.closest('.pet-item');
      if (card && !e.target.closest('a,button')) {
        const pid = card.getAttribute('data-pet-id');
        if (pid) openAnimalProfile(pid);
      }
    });
});

  // Attach click handlers immediately to any existing elements with data-pet-id
  (function attachImmediateHandlers(){
    function bind() {
      document.querySelectorAll('[data-pet-id]').forEach(function(el){
        if (el.__animalProfileBound) return;
        el.addEventListener('click', function(ev){
          // Ignore clicks on links/buttons inside
          if (ev.target.closest('a,button')) return;
          const pid = el.getAttribute('data-pet-id');
          const pname = el.getAttribute('data-pet-name');
          if (pid && openAnimalProfile(pid)) {
            ev.preventDefault();
            ev.stopPropagation();
            return;
          }
          if (pname && openAnimalProfile(pname)) {
            ev.preventDefault();
            ev.stopPropagation();
          }
        });
        el.__animalProfileBound = true;
      });
    }

    // Run once now
    try { bind(); } catch (err) { /* noop */ }

    // Observe DOM changes (useful for dynamic carousel slides) and bind new items
    try {
      const obs = new MutationObserver(function(){ bind(); });
      obs.observe(document.body, { childList: true, subtree: true });
    } catch (e) {
      // MutationObserver may not be available in some environments - that's fine
    }
  })();
</script>
