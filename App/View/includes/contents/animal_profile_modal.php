<?php
/*
 * AmigoPet - Modal de Perfil de Animal (para abrir via JS)
 * Agora carrega dados via AJAX de /api/animal.php
 */
?>

<!-- Modal grande para perfil do animal -->
<div class="modal fade" id="animalProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="row g-0">
          <div class="col-md-6">
            <img id="animalProfileImage" src="" alt="" style="width:100%; height:100%; object-fit:cover;">
          </div>
          <div class="col-md-6">
            <div class="p-4">
              <h2 id="animalProfileName" style="font-weight:700;"></h2>
              <p id="animalProfileBadge" class="text-muted"></p>
              <p id="animalProfileDescription" class="text-secondary"></p>

              <ul class="list-unstyled">
                <li><strong>Raça:</strong> <span id="animalProfileRaca"></span></li>
                <li><strong>Idade:</strong> <span id="animalProfileIdade"></span></li>
                <li><strong>Sexo:</strong> <span id="animalProfileSexo"></span></li>
                <li><strong>Porte:</strong> <span id="animalProfilePorte"></span></li>
              </ul>

              <div class="mt-3">
                <a href="#" id="animalProfileAdoptBtn" class="btn btn-primary me-2" style="background-color:#6FCF97; border:none;">Solicitar Adoção</a>
                <a href="#" id="animalProfileViewBtn" class="btn btn-outline-secondary me-2" target="_blank">Ver Perfil Completo</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
async function openAnimalProfile(petId) {
  if (!petId) return false;
  try {
    const res = await fetch('/api/animal.php?id=' + encodeURIComponent(petId));
    if (!res.ok) return false;
    const pet = await res.json();

    document.getElementById('animalProfileImage').src = pet.foto || '';
    document.getElementById('animalProfileName').textContent = pet.nome || '';
    document.getElementById('animalProfileBadge').textContent = (pet.especie || '') + ' · ' + (pet.racas || '');
    document.getElementById('animalProfileDescription').textContent = pet.descricao || '';
    document.getElementById('animalProfileRaca').textContent = pet.racas || '';
    document.getElementById('animalProfileIdade').textContent = pet.idade || '';
    document.getElementById('animalProfileSexo').textContent = pet.sexo || '';
    document.getElementById('animalProfilePorte').textContent = pet.porte || 'Médio';

    const adoptBtn = document.getElementById('animalProfileAdoptBtn');
    adoptBtn.href = 'adotar.php?id=' + encodeURIComponent(pet.id);
    const viewBtn = document.getElementById('animalProfileViewBtn');
    if (viewBtn) viewBtn.href = 'animal_profile.php?id=' + encodeURIComponent(pet.id);

    const modalEl = document.getElementById('animalProfileModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    return true;
  } catch (e) {
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
