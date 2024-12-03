<?php
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
