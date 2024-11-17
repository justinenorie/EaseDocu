<?php
    require '../../vendor/autoload.php'; 

    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->easedocu->documents;

    $documents = $collection->find();

    foreach ($documents as $doc) {
        echo '<li class="list-item">';
        echo '    <div class="list-item-left">';
        echo '        <input type="checkbox" class="checkbox">';
        echo '        <div class="item-icon">';
        echo '            <img src="../../public/images/icons/doc-certificate.png" alt="">';
        echo '        </div>';
        echo '    </div>';
        echo '    <div class="list-item-right">';
        echo '        <div class="upper-item">';
        echo '            <div class="item-name">';
        echo '                <h2>' . htmlspecialchars($doc['itemName']) . '</h2>';
        echo '            </div>';
        echo '        </div>';
        echo '        <div class="lower-item">';
        echo '            <div class="price"><span>&#8369;</span>' . htmlspecialchars($doc['itemPrice']) . '</div>';
        echo '            <div class="quantity-controls">';
        echo '                <button class="quantity-btn">-</button>';
        echo '                <span class="quantity">0</span>';
        echo '                <button class="quantity-btn">+</button>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</li>';
    }


?>
