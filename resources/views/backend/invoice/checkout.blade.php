<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cibpaynow.gateway.mastercard.com/checkout/version/57/checkout.js" data-error="errorCallback"
        data-complete="completeCallback"></script>
        <style>
            /* Reset some default styling */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Arial, sans-serif;
            }
        
            /* Center content */
            body, html {
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f2f5f7;
            }
        
            /* Container styling */
            .checkout-container {
                text-align: center;
                background: #ffffff;
                padding: 40px;
                width: 300px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        
            /* Heading styling */
            h1 {
                font-size: 24px;
                color: #333;
                margin-bottom: 20px;
            }
        
            /* Button styling */
            .checkout-button {
                display: inline-block;
                padding: 12px 24px;
                background-color: #0070ba;
                color: #ffffff;
                font-size: 16px;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
        
            /* Button hover effect */
            .checkout-button:hover {
                background-color: #005fa3;
            }
        
            /* Mastercard logo */
            .checkout-button img {
                width: 20px;
                height: 20px;
                vertical-align: middle;
                margin-right: 8px;
            }
        </style>
</head>

<body>

    
<div class="checkout-container">
    <h1>Pay with Mastercard</h1>
    <button class="checkout-button" onclick="startCheckout()">
        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard Logo">
        Pay Now
    </button>
</div>

    <script>
        // Fetch session ID from server and start the checkout

        function startCheckout() {
            $.ajax({
                url: "{{ route('checkout.session') }}",
                type: "POST",
                data: {
                    'uuid': "{{ $uuid }}" // Replace with the actual amount as needed
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // Add CSRF token for Laravel
                },
                success: function(data) {
                    
                    if (data.status == "success") {
                        Checkout.configure({
                            session: {
                                id: data.sessionId
                            },
                            interaction: {
                                merchant: {
                                    name: "Your Merchant Name",
                                    address: {
                                        line1: "123 Payment Street",
                                        line2: "Cairo, Egypt"
                                    }
                                }
                            }
                        });
                        Checkout.showPaymentPage();
                    } else {
                        console.error("Error creating session:", data.message);
                        alert("Failed to start checkout session. Please try again.");
                    }
                },
                error: function(error) {
                    console.error("AJAX error:", error);
                    alert("Error initiating checkout. Please try again later.");
                }
            });
        }

        // Define the error callback
        function errorCallback(error) {
            console.error("Error during checkout:", error);
            alert("Payment error. Please try again.");
        }

        // Define the complete callback
        function completeCallback(result) {
            if (result.status === "SUCCESS") {
                // Send the transaction ID to the server for verification and logging
                $.ajax({
                    url: "{{route('checkout.verify')}}", // Server-side endpoint
                    type: 'POST',
                    data: JSON.stringify({
                        transactionId: result.transaction.id,
                        uuid:"{{$uuid}}"
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Payment has been recorded successfully!");
                            // window.location.href = "order_success.php"; // Redirect to a success page
                        } else {
                            alert("Failed to record payment. Please contact support.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error recording payment:", error);
                        alert("An error occurred while recording your payment. Please contact support.");
                    }
                });

            } else {
                alert("Payment did not complete successfully. Please check and try again.");
            }
        }
    </script>

</body>

</html>
