</div>
<p class="copyright text-thin text-muted"> &copy; <?php echo date('Y') ?> <a href="https://bennito254.com">Bennito254</a> <span>•</span> All Rights Reserved.</p>
</div>

<!-- scripts -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js//jquery.slimscroll.min.js"></script>
<script src="assets/js/simcify.min.js"></script>
<!-- custom scripts -->
<script src="assets/js/app.js"></script>
<script>
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            return $('form').parsley().validate();
        });
    });
</script>
</body>
</html>