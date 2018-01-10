var fundFee = {
    'LIK75': 0.0086,
    'LXK00': 0.0073,
    'LSK00': 0.0096,
    'LMK25': 0.0129,
    'LLK50': 0.0161,
    'LXK75': 0.0163,
    'NPK00': 0.0085,
    'NPK25': 0.0142,
    'NPK50': 0.0151,
    'NPK75': 0.0161,
    'SIK75': 0.0049,
    'SEK00': 0.0056,
    'SEK25': 0.0126,
    'SEK50': 0.0149,
    'SEK75': 0.0164,
    'SWK00': 0.0039,
    'SWK25': 0.0104,
    'SWK50': 0.0110,
    'SWK75': 0.0113,
    'SWK99': 0.0089,
    'average': 0.0129
};

var format = function(num) {
    if (!LANGCODE) {
        return num;
    }

    switch(LANGCODE) {
        case 'et':
            if (num > 9999) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }
            break;
        case 'en':
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            break;
    }

    return num;
};

var calculateSaving = function() {
    var age = $('#age').val();
    var netWage = $('#netWage').val();

    var selectedFund = $('#comparisonFund').val();
    var comparisonFund = fundFee[selectedFund];
    var comparisonFundPercentage = (comparisonFund * 100).toFixed(2) + "%";
    if (LANGCODE == "et") {
        comparisonFundPercentage = comparisonFundPercentage.replace(".", ",");
    }

    var grossWage = netWage / 0.8;

    var pastAverageReturn = 1.039;
    var pastSalaryGrowth = 1.03;
    var maximumContributionYears = new Date().getFullYear() - 2003;

    var presentValueOfPensionFund = Math.ceil(((grossWage * 0.06 * 12) / (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) * ((Math.pow(pastAverageReturn, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears))) - (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) / (pastAverageReturn - pastSalaryGrowth));

    var averageFundFee = fundFee.average;
    var marketReturn = 1.05;
    var salaryGrowth = 1.03;

    var futureValueOfPensionFund = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - comparisonFund, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - comparisonFund - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - comparisonFund, (65 - (Math.max(0, Math.min(age, 65))))))));


    var tulevaFee = 0.005;

    var totalSavingWithTuleva = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - tulevaFee - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65))))))) - futureValueOfPensionFund);

    var futureValueWithTuleva = futureValueOfPensionFund + totalSavingWithTuleva;

    $('#future-value').text(format(futureValueOfPensionFund) + " €");
    $('#future-value-tuleva').text(format(futureValueWithTuleva) + " €");
    $('#tuleva-saving').text(format(totalSavingWithTuleva) + " €");
    $('#fund-fee').text(comparisonFundPercentage);
}

calculateSaving();
