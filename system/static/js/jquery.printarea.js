(function($) {
    var printAreaCount = 0;
    $.fn.printArea = function() {
        var ele = $(this);
        var idPrefix = "printArea_";
        removePrintArea(idPrefix + printAreaCount);
        printAreaCount++;
        var iframeId = idPrefix + printAreaCount;
        var iframeStyle = 'position:absolute;width:0px;height:0px;left:-500px;top:-500px;';
        iframe = document.createElement('IFRAME');
        $(iframe).attr({
            style: iframeStyle,
            id: iframeId
        });
        document.body.appendChild(iframe);
        var doc = iframe.contentWindow.document;
        $(document).find("link").filter(function() {
            return $(this).attr("rel").toLowerCase() == "stylesheet";
        }).each(
            function() {
                // 这里是在将网页中的所有css引入，即打印区域的css如果是link进来的，可以设置在网页的任何位置
                doc.write('<link type="text/css" rel="stylesheet" href="' + $(this).attr("href") + '" >');
            });
        doc.write($(ele).prop('outerHTML')); // 将待打印元素原封不动的引入
        doc.close();
        var frameWindow = iframe.contentWindow;
        frameWindow.close();
        frameWindow.focus();
        // 必须等待frame加载完成后再打印，否则可能在某些浏览器中打印不出东西。
        frameWindow.onload = function() {
          frameWindow.print();
        };
    }
    var removePrintArea = function(id) {
        $("iframe#" + id).remove();
    };
})(jQuery);