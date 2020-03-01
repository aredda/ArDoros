/**
 * REQUIRES Jquery
 */

/**
 * Apply the effect in a consecutive manner
 */
function dominoEffect(selector, effectSpeed, direction = 'out', itemIndex = 0, animationComplete = null) {
    if (itemIndex >= selector.length) {
        if (animationComplete != null)
            animationComplete();

        return;
    }

    var complete = () => {
        // Remove item
        if (direction == 'out')
            selector.eq(itemIndex).remove();

        dominoEffect(selector, effectSpeed, direction, itemIndex + 1, animationComplete);
    };

    if (direction == 'out')
        selector.eq(itemIndex).fadeOut(effectSpeed, complete);
    else
        selector.eq(itemIndex).fadeIn(effectSpeed, complete);
}

/**
 * Showing notifications
 */
function popNotification(header, message, type) 
{
    $(document).ready(function () {
        // Lay over
        $(".overlay").fadeIn(400);
        // Change notification properties
        $(".notification-header").html(header);
        $(".notification-header").removeClass('bg-grd-second bg-grd-error');
        $(".notification-header").addClass(type == 'success' ? 'bg-grd-second' : 'bg-grd-error');
        $(".notification-body").html(message);
        // Show notification 
        $(".notification").fadeIn(400);
    });
}