<div class="footer-container">
            <footer class="wrapper">
                <img src="img/logox.png" alt="PcLab logo">
            </footer>
</div>

<script type="text/javascript">
            $(document).ready(function() {
                $("#jMenu").jMenu();
            });
          
          var eventType = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent) ) ? 'touchend' : 'click';
          $('.main-container','#jMenu').on(eventType, function(e) {
                $.jMenu._closeAll();
                $('.jMenu li a').each.css("color","white");
          });
</script>