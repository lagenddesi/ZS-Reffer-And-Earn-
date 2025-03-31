        </div> <!-- Close container from header -->
        
        <footer class="bg-dark text-white mt-5 py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5><?php echo SITE_NAME; ?></h5>
                        <p>Investment Platform</p>
                    </div>
                    <div class="col-md-3">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo BASE_URL; ?>index.php" class="text-white">Home</a></li>
                            <li><a href="<?php echo BASE_URL; ?>about.php" class="text-white">About</a></li>
                            <li><a href="<?php echo BASE_URL; ?>contact.php" class="text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Legal</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo BASE_URL; ?>terms.php" class="text-white">Terms</a></li>
                            <li><a href="<?php echo BASE_URL; ?>privacy.php" class="text-white">Privacy</a></li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
    </body>
</html>
