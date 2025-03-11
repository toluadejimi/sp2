@if (session()->has('message'))
    <!-- Success Modal -->
    <div class="tf-panel up show" style="display: block;">
        <div class="panel_overlay"></div>
        <div class="panel-box panel-up overflow-hidden">
            <div class="header bg_white_color">
                <div class="tf-container">
                    <div class="tf-statusbar d-flex justify-content-center align-items-center">
                        <a href="#" class="clear-panel"> <i class="icon-close1"></i> </a>
                        <h3>Success</h3>
                    </div>
                </div>
            </div>
            <div class="panel-content mb-5 mt-5">
                <div class="tf-container">
                    {{ session()->get('message') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <!-- Error Modal -->
    <div class="tf-panel up show" style="display: block;">
        <div class="panel_overlay"></div>
        <div class="panel-box panel-up overflow-hidden">
            <div class="header bg_white_color">
                <div class="tf-container">
                    <div class="tf-statusbar d-flex justify-content-center align-items-center">
                        <a href="#" class="clear-panel"> <i class="icon-close1"></i> </a>
                        <h3>Error</h3>
                    </div>
                </div>
            </div>
            <div class="panel-content mb-5 mt-5">
                <div class="tf-container">
                    {{ session()->get('error') }}
                </div>
            </div>
        </div>
    </div>
@endif


<script>
    document.addEventListener("DOMContentLoaded", function () {
        let modal = document.querySelector('.tf-panel.show');

        if (modal) {
            modal.style.display = "block"; // Ensure it's visible

            setTimeout(() => {
                modal.style.display = "none"; // Auto-hide after 5 seconds
            }, 5000);
        }

        // Close modal when clicking the close button
        document.querySelectorAll('.clear-panel').forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                this.closest('.tf-panel').style.display = "none";
            });
        });
    });
</script>
