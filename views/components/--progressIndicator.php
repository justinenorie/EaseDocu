<?php

$currentStatus = "unpaid"; 

$statusSteps = [
    'unpaid' => [
        'image' => '../../public/images/icons/warning.png',
        'order' => 1
    ],
    'paid' => [
        'image' => 'path/to/paid-icon.png',
        'order' => 2
    ],
    'processing' => [
        'image' => 'path/to/processing-icon.png',
        'order' => 3
    ],
    'ready' => [
        'image' => 'path/to/ready-icon.png',
        'order' => 4
    ]
];

function renderProgressIndicator($currentStatus, $statusSteps) {
    $currentOrder = $statusSteps[$currentStatus]['order'];
    ?>
    <div class="status-line">
        <?php foreach ($statusSteps as $status => $info): ?>
            <?php
            $circleClass = '';
            if ($info['order'] < $currentOrder) {
                $circleClass = 'completed';
            } elseif ($info['order'] === $currentOrder) {
                $circleClass = 'current';
            } else {
                $circleClass = 'upcoming';
            }
            ?>
            <div class="circle <?php echo $circleClass; ?>">
                <?php if ($info['order'] === $currentOrder): ?>
                    <img src="<?php echo $info['image']; ?>" alt="<?php echo ucfirst($status); ?>">
                <?php else: ?>
                    <?php echo $info['order']; ?>
                <?php endif; ?>
                <span class="status-label"><?php echo ucfirst($status); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function renderStatusMessage($currentStatus) {
    $messages = [
        'unpaid' => [
            'title' => 'Payment process expired in 24 hours',
            'description' => 'Please complete your payment to proceed with your request.'
        ],
        'paid' => [
            'title' => 'Payment successfully received',
            'description' => 'Thank you for your payment. Your request will be processed shortly.'
        ],
        'processing' => [
            'title' => 'Your document is on the process......',
            'description' => 'We are currently working on your document request.'
        ],
        'ready' => [
            'title' => 'Your document is available to pick-up',
            'description' => 'Your document is ready for pick-up on January 1, 2025, 3:00 PM Onwards.'
        ]
    ];

    $message = $messages[$currentStatus];
    ?>
    <div class="status-message">
        <h3><?php echo $message['title']; ?></h3>
        <p><?php echo $message['description']; ?></p>
    </div>
    <?php
}
?>