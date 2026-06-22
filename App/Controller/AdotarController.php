<?php

namespace App\Controller;

use FW\Controller\Action;

class AdotarController extends Action
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get pet ID from query parameter
        $petId = isset($_GET['id']) ? intval($_GET['id']) : null;
        
        // Pass pet ID to the view
        $this->render('includes/contents/adotar_content', 'dashboard', ['petId' => $petId]);
    }

    public function validaAutenticacao()
    {
        // Adotar page is public, no authentication required
        // This method is required by the parent class but does nothing for this public page
    }
}
