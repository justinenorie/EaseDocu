<?php
function renderDocumentListItem($document) {
    return '
    <li class="list-item">
        <div class="list-item-left">
            <p>x3</p>
            <div class="item-icon">
                <img src="../../public/images/icons/doc-certificate.png" alt="">
            </div>
        </div>
        <div class="list-item-right">
            <div class="upper-item">
                <div class="item-name">
                    <h2>' . htmlspecialchars($document['document']) . '</h2>
                </div>
            </div>
            <div class="lower-item">
                <div class="price"><span>&#8369;</span>' . htmlspecialchars($document['price']) . '</div>
            </div>
        </div>
    </li>';
}
?>
