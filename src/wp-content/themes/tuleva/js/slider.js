/* global mixpanel */

function calculateSaving(age, netWage) {
    var grossWage = netWage / 0.8,
        pastAverageReturn = 1.039,
        pastSalaryGrowth = 1.03,
        maximumContributionYears = new Date().getFullYear() - 2003,
        presentValueOfPensionFund = Math.ceil(grossWage * 0.06 * 12 / Math.pow(pastSalaryGrowth, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears)) * (Math.pow(pastAverageReturn, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears)) - Math.pow(pastSalaryGrowth, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears))) / (pastAverageReturn - pastSalaryGrowth)),
        averageFundFee = 0.0108,
        marketReturn = 1.05,
        salaryGrowth = 1.03,
        futureValueOfPensionFund = Math.ceil(grossWage * 0.06 * 12 * (Math.pow(marketReturn - averageFundFee, 65 - Math.max(0, Math.min(age, 65))) - Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65)))) / (marketReturn - averageFundFee - salaryGrowth) + presentValueOfPensionFund * Math.pow(marketReturn - averageFundFee, 65 - Math.max(0, Math.min(age, 65)))),
        tulevaFee = 0.0034,
        totalSavingWithTuleva = Math.ceil(grossWage * 0.06 * 12 * (Math.pow(marketReturn - tulevaFee, 65 - Math.max(0, Math.min(age, 65))) - Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65)))) / (marketReturn - tulevaFee - salaryGrowth) + presentValueOfPensionFund * Math.pow(marketReturn - tulevaFee, 65 - Math.max(0, Math.min(age, 65))) - futureValueOfPensionFund);

    $('#tuleva-calculator-result').html(totalSavingWithTuleva.toLocaleString('et-EE'));
}

$('#tuleva-calculator-wage').rangeslider({
    polyfill: false,

    // Default CSS classes
    rangeClass: 'rangeslider',
    disabledClass: 'rangeslider--disabled',
    horizontalClass: 'rangeslider--horizontal',
    verticalClass: 'rangeslider--vertical',
    fillClass: 'rangeslider__fill',
    handleClass: 'rangeslider__handle',

    onInit: function () {
        this.onSlide(0, 1000);
    },

    onSlide: function (position, wage) {
        var age = $('#tuleva-calculator-age').val();

        $('#wage').html(wage);
        calculateSaving(age, wage);
    },

    onSlideEnd: function (position, wage) {
        mixpanel.track("Wage slider interaction", {
            wage: wage
        });
    }
});

$('#tuleva-calculator-age').rangeslider({
    polyfill: false,

    // Default CSS classes
    rangeClass: 'rangeslider',
    disabledClass: 'rangeslider--disabled',
    horizontalClass: 'rangeslider--horizontal',
    verticalClass: 'rangeslider--vertical',
    fillClass: 'rangeslider__fill',
    handleClass: 'rangeslider__handle',

    onInit: function () {
        this.onSlide(0, 30);
    },

    onSlide: function (position, age) {
        var wage = $('#tuleva-calculator-wage').val();

        $('#age').html(age);
        calculateSaving(age, wage);
    },

    onSlideEnd: function (position, age) {
        mixpanel.track("Age slider interaction", {
            age: age
        });
    }
});
