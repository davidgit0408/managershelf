
  <script>
        $(document).ready(function(){
            $('#sidenav-main .nav-item ul').hide();
            $("#sidenav-main .nav-item").click(function(){
                $(this).find('ul').toggle('slow');
            });
        });
  </script>
</body>

</html>