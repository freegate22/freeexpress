$(function() {
    $("a.social_oauth").on("click", function() {
        var option = "left=50, top=50, width=600, height=450, scrollbars=1";

        window.open(this.href, "win_social_login", option);

        return false;
    });
});