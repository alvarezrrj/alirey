<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
      window.opener.postMessage({oAuthResult: '{{ $result }}'}, '*');
      window.close();
    </script>
  </head>
  <body>

  </body>
</html>
