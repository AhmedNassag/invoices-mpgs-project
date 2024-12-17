<html>
    <head>
<script src="https://banquemisr.gateway.mastercard.com/static/checkout/checkout.min.js" data-error="errorCallback" data-complete="completeCallback" data-cancel="cancelCallback"></script>
        <script type="text/javascript">
            function errorCallback(error) {
                alert("error");
                  console.log(JSON.stringify(error));
            }
            function cancelCallback() {
                 alert('Payment cancelled');
            }
            function completeCallback() {


            }
            Checkout.configure({
              session: {
             id: "{{$sessionId}}",
       			}
            });
            document.addEventListener("DOMContentLoaded", function() {
              // Show loading spinner
            const embedTarget           = document.getElementById('embed-target');
                  embedTarget.innerHTML = `<img src="{{ asset('images/tube-spinner.svg') }}" alt="Loading..." />`;

            // Wait and then embed the payment form
            setTimeout(() => {
                Checkout.showEmbeddedPage('#embed-target');
            }, 2000);

            // Hide spinner after payment form appears
            const observer = new MutationObserver(() => {
                const iframe = embedTarget.querySelector('iframe');
                if (iframe) {
                    embedTarget.querySelector('img').style.display = 'none'; // Hide the spinner
                }
            });

            observer.observe(embedTarget, { childList: true, subtree: true });
        });
        </script>
    </head>
    <body>

      <div id = "embed-target">
    </div>
      {{-- <input type="button" value="Pay with Embedded Page" onclick="Checkout.showEmbeddedPage('#embed-target');" /> --}}
      {{-- <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" /> --}}
    </body>
</html>
