    </main>
    <footer><p>Copyright &copy; <?php echo date("Y", time()); ?>, <a href="http://www.kamallatif.com"  target="_blank">Kamal Latif</a></p></footer>
     <!-- end #wrapper --></div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>