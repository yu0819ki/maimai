jQuery(document).ready(function(){
    jQuery('.js-md').each(function(){
        var $this = jQuery(this);
        $this.html(marked($this.text()));
    });
    jQuery(document).on('click', '.js-external-content-wrapper a', function(){
        var $this = jQuery(this);
        $this.attr('target', '_blank');
    });
});
