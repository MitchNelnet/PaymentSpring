(function($){
	$(document).ready(function() {
		var $page_template = $('#page_template');
	  var $metabox1 = $('#main-color-meta-box');
	  var $metabox2 = $('#accent-color-meta-box');
	  //var $metabox3 = $('#watermark-meta-box');
    var $metabox4 = $('#position-bgimage-meta-box');
    var $metabox5 = $('#head-bgcolor-meta-box');

    $page_template.change(function() {
      if ($(this).val() == 'generic-partner-template.php' || $(this).val() == 'uniform_flat_header_landing.php') {
        $metabox1.show();
        $metabox2.show();
        //$metabox3.show();
      } else {
        $metabox1.hide();
        $metabox2.hide();
        //$metabox3.hide();
      }

      if ($(this).val() == 'uniform_flat_header.php' || $(this).val() == 'uniform_flat_header_landing.php') {
        $metabox4.show();
        $metabox5.show();
      } else {
        $metabox4.hide();
        $metabox5.hide();
      }
    }).change();
	});
})(jQuery);