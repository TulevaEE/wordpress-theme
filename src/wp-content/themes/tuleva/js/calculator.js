/* global LANGCODE */

var fundFee = {
    'LIK75': 0.0063,
    'LLK50': 0.0101,
    'LMK25': 0.0084,
    'LSK00': 0.0069,
    'LXK00': 0.0061,
    'LXK75': 0.0098,
    'NPK00': 0.0097,
    'NPK25': 0.0155,
    'NPK50': 0.0158,
    'NPK75': 0.0162,
    'SEK00': 0.0049,
    'SEK25': 0.0094,
    'SEK50': 0.0094,
    'SEK75': 0.0092,
    'SIK75': 0.004,
    'SWK00': 0.0037,
    'SWK25': 0.0065,
    'SWK50': 0.0067,
    'SWK75': 0.007,
    'SWK99': 0.007,
    'average': 0.0086
},

    format = function (num) {
        if (!LANGCODE) {
            return num;
        }

        switch (LANGCODE) {
            case 'et':
                if (num > 9999) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                }
                break;
            case 'en':
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            default:
                break;
        }

        return num;
    },

    calculateSaving = function () {
        var age = $('#age').val(),
            netWage = $('#netWage').val(),
            selectedFund = $('#comparisonFund').val(),
            comparisonFund = fundFee[selectedFund],
            comparisonFundPercentage = (comparisonFund * 100).toFixed(2) + "%",
            grossWage = netWage / 0.8,
            pastAverageReturn = 1.039,
            pastSalaryGrowth = 1.03,
            maximumContributionYears = new Date().getFullYear() - 2003,
            presentValueOfPensionFund = Math.ceil(grossWage * 0.06 * 12 / Math.pow(pastSalaryGrowth, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears)) * (Math.pow(pastAverageReturn, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears)) - Math.pow(pastSalaryGrowth, Math.min(Math.max(0, Math.min(age, 65)) - 23, maximumContributionYears))) / (pastAverageReturn - pastSalaryGrowth)),
            marketReturn = 1.05,
            salaryGrowth = 1.03,
            futureValueOfPensionFund = Math.ceil(grossWage * 0.06 * 12 * (Math.pow(marketReturn - comparisonFund, 65 - Math.max(0, Math.min(age, 65))) - Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65)))) / (marketReturn - comparisonFund - salaryGrowth) + presentValueOfPensionFund * Math.pow(marketReturn - comparisonFund, 65 - Math.max(0, Math.min(age, 65)))),
            tulevaFee = 0.0045,
            totalSavingWithTuleva = Math.ceil(grossWage * 0.06 * 12 * (Math.pow(marketReturn - tulevaFee, 65 - Math.max(0, Math.min(age, 65))) - Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65)))) / (marketReturn - tulevaFee - salaryGrowth) + presentValueOfPensionFund * Math.pow(marketReturn - tulevaFee, 65 - Math.max(0, Math.min(age, 65))) - futureValueOfPensionFund),
            futureValueWithTuleva = futureValueOfPensionFund + totalSavingWithTuleva;

        if (LANGCODE === "et") {
            comparisonFundPercentage = comparisonFundPercentage.replace(".", ",");
        }

        $('#future-value').text(format(futureValueOfPensionFund) + " €");
        $('#future-value-tuleva').text(format(futureValueWithTuleva) + " €");
        $('#tuleva-saving').text(format(totalSavingWithTuleva) + " €");
        $('#fund-fee').text(comparisonFundPercentage);
    }

calculateSaving();
