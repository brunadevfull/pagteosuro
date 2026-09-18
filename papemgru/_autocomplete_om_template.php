<?php
// Template compartilhado do campo de autocomplete de OM (OC/UPAG).
// Variaveis esperadas antes do include: $campoId, $campoName, $campoPlaceholder, $campoMarginTopExtra (opcional).
include __DIR__ . "/_om_recolhedora_tags.php";
$campoMarginTopExtra = $campoMarginTopExtra ?? "";
?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
  <script>
  $( function() {
    var availableTags = <?php echo json_encode($availableTagsOM, JSON_UNESCAPED_UNICODE); ?>;
    $( "#<?php echo $campoId; ?>" ).autocomplete({
      source: availableTags
    });
  } );
  </script>

<div class="ui-widget">
  <label for="sisresOutros1"></label>
  <input placeholder="<?php echo $campoPlaceholder; ?>" name="<?php echo $campoName; ?>" style="display:none; width: 950px; height:38; <?php echo $campoMarginTopExtra; ?>margin-left:25px; padding: 8px 12px;" id="<?php echo $campoId; ?>">
</div>
