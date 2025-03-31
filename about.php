<?php
require_once __DIR__.'/includes/header.php';
$title = 'About Us';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>About <?php echo SITE_NAME; ?></h4>
        </div>
        <div class="card-body">
            <h5>Our Investment Platform</h5>
            <p>Welcome to <?php echo SITE_NAME; ?>, a trusted investment platform that has been serving clients since 2023. We specialize in providing secure and profitable investment opportunities.</p>
            
            <h5 class="mt-4">Our Mission</h5>
            <p>To empower our members with financial growth opportunities through innovative investment solutions while maintaining transparency and security.</p>
            
            <h5 class="mt-4">Key Features</h5>
            <ul>
                <li>Multiple investment packages with varying returns</li>
                <li>Secure transaction processing</li>
                <li>Referral bonus program</li>
                <li>24/7 customer support</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
