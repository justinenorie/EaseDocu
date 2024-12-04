<?php
require '../../vendor/autoload.php';


try {
    $mongo = new MongoDB\Client($_ENV['MONGODB_TOKEN']);
    $collection = $mongo->easedocu->documentList;
    
    // Initial fetch of documents
    $documents = $collection->find()->toArray();
} catch (Exception $e) {
    $documents = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Document List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div id="document-list">
        <?php foreach ($documents as $doc): ?>
            <div class="document-item" data-id="<?= (string)$doc['_id'] ?>">
                <?= htmlspecialchars($doc['document'] ?? 'N/A') ?>
            </div>
        <?php endforeach; ?>
    </div>

    <p id="debug-info">Waiting to start...</p>

    <script>
    $(document).ready(function() {
        let fetchCount = 0;

        function fetchDocuments() {
            fetchCount++;
            
            $('#debug-info').text(`Fetching documents... (Attempt: ${fetchCount})`);
            
            $.ajax({
                url: 'fetch_documents.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let $list = $('#document-list');
                    $list.empty();
                    
                    data.forEach(function(doc) {
                        $list.append(
                            `<div class="document-item" data-id="${doc._id}">
                                ${doc.document}
                             </div>`
                        );
                    });
                    
                    $('#debug-info').text(`Last updated: ${new Date().toLocaleTimeString()} (Attempts: ${fetchCount})`);
                },
                error: function(xhr, status, error) {
                    $('#debug-info').text(`Error fetching documents: ${error} (Attempts: ${fetchCount})`);
                }
            });
        }

        // Fetch documents every 3 seconds
        setInterval(fetchDocuments, 1000);

        // Initial fetch
        fetchDocuments();
    });
    </script>
</body>
</html>