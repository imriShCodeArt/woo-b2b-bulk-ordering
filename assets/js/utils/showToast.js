/**
 * Displays a temporary toast notification.
 *
 * @param {jQuery} $ - jQuery instance
 * @param {string} message - The message to display
 * @param {string} [type="success"] - Type: "success", "error", "info", "warning"
 */
export default function showToast($, message, type = "success") {
  const toast = $('<div class="b2b-toast ' + type + '">' + message + '</div>');
  $("body").append(toast);
  setTimeout(() => toast.fadeOut(300, () => toast.remove()), 3000);
}
