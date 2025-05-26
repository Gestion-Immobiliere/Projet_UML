<!DOCTYPE html>
<html>
<head>
  <title>Test Paiement Stripe</title>
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
  <form id="payment-form">
    <div id="card-element"></div>
    <button type="submit">Payer</button>
  </form>

  <script>
    const stripe = Stripe('pk_test_51ROh3cFJHz1Zmgux6kXAyf93c8Skf7SpXdqF5bLNFf282LB5VjA71OQrD4SG3lPoFetFzTqFc9R4NnBRtAeWOsq300pLHEFLhg');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const clientSecret = "pi_3ROijJFJHz1Zmgux08B5K9hd_secret_KwGRqs3XKrxWZSubshdEAWOPI";

    document.getElementById('payment-form').addEventListener('submit', function(event) {
      event.preventDefault();
      stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: card,
          billing_details: { name: 'Testeur' },
        },
      }).then(function(result) {
        if (result.error) {
          alert(result.error.message);
        } else if (result.paymentIntent.status === 'succeeded') {
          alert('Paiement réussi !');
        }
      });
    });
  </script>
</body>
</html>
