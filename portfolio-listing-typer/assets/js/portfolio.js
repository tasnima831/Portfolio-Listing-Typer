(function ($) {
  $(document).on('change', '.plt-filter-select', function () {
    var slug = $(this).val();
    var $wrap = $(this).closest('.plt-filter').nextAll('.plt-wrapper').first();
    $wrap.find('.plt-item').each(function () {
      var cats = ($(this).attr('data-cats') || '').split(' ');
      if (!slug || slug === '') {
        $(this).show();
      } else {
        if (cats.indexOf(slug) !== -1) $(this).show();
        else $(this).hide();
      }
    });
  });
})(jQuery);
