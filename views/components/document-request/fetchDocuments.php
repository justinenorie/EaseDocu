<?php
// views/components/fetchDocuments.php

require_once __DIR__ . '../../../../controller/RequestDataController.php';
require_once __DIR__ . '/documentListItem.php';


function renderDocumentsList() {
    try {
        $controller = new EaseDocuController();
        $documents = $controller->getAllDocuments();
        $output = '';
        foreach ($documents as $doc) {
            $output .= renderDocumentListItem($doc);
        }
        
        return $output;
    } catch (Exception $e) {
        error_log("Error fetching documents: " . $e->getMessage());
        return '<li class="error">Unable to load documents. Please try again later.</li>';
    }
}

// If this file is called directly (AJAX), return JSON
// if (basename($_SERVER['PHP_SELF']) === 'fetchDocuments.php') {
//     header('Content-Type: application/json');
//     echo json_encode([
//         'success' => true,
//         'html' => renderDocumentsList()
//     ]);
//     exit;
// }