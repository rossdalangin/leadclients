function calculateSolarROI() {
    var bill = document.getElementById('monthly_bill').value;
    var resultDiv = document.getElementById('gp-result');
    if (bill) {
        var yearlySavings = bill * 12 * 0.8;
        var paybackYears = 15000 / (bill * 12);
        resultDiv.innerHTML = "<p>Estimated Yearly Savings: $" + yearlySavings.toFixed(2) + "</p><p>Payback Period: " + paybackYears.toFixed(1) + " years</p>";
    }
}

function checkSymptoms() {
    var symptoms = document.getElementById('symptoms').value;
    var nonce = document.getElementById('gp_medical_nonce').value;
    var output = document.getElementById('ai-medical-advice');

    output.innerHTML = "Consulting AI Triage...";

    jQuery.post(gp_ajax.ajaxurl, {
        action: 'gp_check_symptoms',
        symptoms: symptoms,
        nonce: nonce
    }, function(res) {
        if(res.success) {
            output.innerHTML = res.data;
        } else {
            output.innerHTML = "Error: " + res.data;
        }
    });
}
