fundFee = new Array();
fundFee["LIK75"] = 0.0039;
fundFee["LXK00"] = 0.0062802;
fundFee["LSK00"] = 0.00798;
fundFee["LMK25"] = 0.01064;
fundFee["LLK50"] = 0.0133;
fundFee["LXK75"] = 0.0133;
fundFee["NPK00"] = 0.0075;
fundFee["NPK25"] = 0.0137;
fundFee["NPK50"] = 0.0147;
fundFee["NPK75"] = 0.0156;
fundFee["SIK75"] = 0.0029;
fundFee["SEK00"] = 0.0049;
fundFee["SEK25"] = 0.010115;
fundFee["SEK50"] = 0.011671;
fundFee["SEK75"] = 0.013227;
fundFee["SWK00"] = 0.0029;
fundFee["SWK25"] = 0.0087;
fundFee["SWK50"] = 0.0092;
fundFee["SWK75"] = 0.0092;
fundFee["SWK99"] = 0.0049;
fundFee["average"] = 0.0108;

function calculateSaving() {
    var age = $('#age').val();
    var netWage = $('#netWage').val();

    var selectedFund = $('#comparisonFund').val();
    var comparisonFund = fundFee[selectedFund];
    var comparisonFundPercentage = (comparisonFund * 100).toFixed(2) + "%";
        comparisonFundPercentage = comparisonFundPercentage.replace(".", ",");

    var grossWage = netWage / 0.8;

    var pastAverageReturn = 1.039;
    var pastSalaryGrowth = 1.03;
    var maximumContributionYears = new Date().getFullYear() - 2003;

    var presentValueOfPensionFund = Math.ceil(((grossWage * 0.06 * 12) / (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) * ((Math.pow(pastAverageReturn, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears))) - (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) / (pastAverageReturn - pastSalaryGrowth));

    var averageFundFee = 0.0108;
    var marketReturn = 1.05;
    var salaryGrowth = 1.03;

    var futureValueOfPensionFund = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - comparisonFund, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - comparisonFund - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - comparisonFund, (65 - (Math.max(0, Math.min(age, 65))))))));


    var tulevaFee = 0.0034;

    var totalSavingWithTuleva = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - tulevaFee - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65))))))) - futureValueOfPensionFund);

    var futureValueWithTuleva = futureValueOfPensionFund + totalSavingWithTuleva;

    $('#future-value').html(futureValueOfPensionFund + " €");
    $('#future-value-tuleva').html(futureValueWithTuleva + " €");
    $('#tuleva-saving').html(totalSavingWithTuleva + " €");
    $('#fund-fee').html(comparisonFundPercentage);
}

calculateSaving();
