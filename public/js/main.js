jQuery(document).ready(function(){
    jQuery('.js-md').each(function(){
        var $this = jQuery(this);
        $this.html(marked($this.text()));
    });
});