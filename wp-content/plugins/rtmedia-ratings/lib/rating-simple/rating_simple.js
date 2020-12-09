(
        function($) {
            $.fn.webwidget_rating_simple = function(p) {
                var p = p || {};
                var b = p && p.rating_star_length ? p.rating_star_length : "5";
                var c = p && p.rating_function_name ? p.rating_function_name : "";
                var e = p && p.rating_initial_value ? p.rating_initial_value : "";
                var k = p && p.rating_extra_args ? p.rating_extra_args : {};
                var f = "";
                var g = $(this);
                b = parseInt(b);
                init();
                g.next("ul").children("li").hover(function() {
                    $(this).parent().children("li").children("div").removeClass("dashicons-star-filled");
                    $(this).parent().children("li").children("div").addClass("dashicons-star-empty");
                    var a = $(this).parent().children("li").index($(this));
                    $(this).parent().children("li").children("div").slice(0, a + 1).removeClass("dashicons-star-empty");
                    $(this).parent().children("li").children("div").slice(0, a + 1).addClass("dashicons-star-filled");
                }, function() {
                    
                });
                g.next("ul").children("li").click(function() {
                    var a = $(this).parent().children("li").index($(this));
                    f = a + 1;
                    g.val(f);
                    if (c != "") {
                        //eval(c + "(" + g.val() + "," + k + ")")
                        window[c](g.val(), k);
                    }
                });
                g.next("ul").hover(function() {
                }, function() {
                    $(this).children("li").children("div").removeClass("dashicons-star-filled");
                    $(this).children("li").children("div").addClass("dashicons-star-empty");

                    $(this).children("li").children("div").slice(0, f).removeClass("dashicons-star-empty");
                    $(this).children("li").children("div").slice(0, f).addClass("dashicons-star-filled");
                });

                function init() {
                    g.css("float", "left");
                    var a = $("<ul>");
                    a.attr("class", "webwidget_rating_simple");
                    for (var i = 1; i <= b; i++) {
                        a.append('<li ><div class="dashicons dashicons-star-empty"></div></li>')
                    }
                    a.insertAfter(g);
                    if (e != "") {
                        f = e;
                        g.val(e);
                    g.next("ul").children("li").children("div").slice(0, f).removeClass("dashicons-star-empty");
                    g.next("ul").children("li").children("div").slice(0, f).addClass("dashicons-star-filled");
                    }
                }}
        }
)(jQuery);