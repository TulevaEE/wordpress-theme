var averageFundFee = 1.09 / 100;

var format = function (num) {
    if (!window.LANGCODE) {
        return num;
    }

    switch (window.LANGCODE) {
        case "et":
            if (num > 9999) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }
            break;
        case "en":
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        default:
            break;
    }

    return num;
};

var appendFunds = function () {
    $.each(calculatorFunds, function (key, value) {
        $("#comparisonFund").append(
            $("<option></option>")
                .attr("value", value["fee"])
                .text(value["name"])
        );
    });
};

var getFee = function () {
    var fee = $("#comparisonFund").val();

    if (fee === "average") {
        return averageFundFee;
    }

    return fee;
};

var calculateSaving = function () {
    var age = $("#age").val();
    var netWage = $("#netWage").val();
    var comparisonFund = getFee();
    var comparisonFundPercentage = (comparisonFund * 100).toFixed(2) + "%";
    var grossWage = netWage / 0.8;
    var pastAverageReturn = 1.039;
    var pastSalaryGrowth = 1.03;
    var maximumContributionYears = new Date().getFullYear() - 2003;
    var presentValueOfPensionFund = Math.ceil(
        (((grossWage * 0.06 * 12) /
            Math.pow(
                pastSalaryGrowth,
                Math.min(
                    Math.max(0, Math.min(age, 65)) - 23,
                    maximumContributionYears
                )
            )) *
            (Math.pow(
                pastAverageReturn,
                Math.min(
                    Math.max(0, Math.min(age, 65)) - 23,
                    maximumContributionYears
                )
            ) -
                Math.pow(
                    pastSalaryGrowth,
                    Math.min(
                        Math.max(0, Math.min(age, 65)) - 23,
                        maximumContributionYears
                    )
                ))) /
            (pastAverageReturn - pastSalaryGrowth)
    );
    var marketReturn = 1.05;
    var salaryGrowth = 1.03;
    var futureValueOfPensionFund = Math.ceil(
        (grossWage *
            0.06 *
            12 *
            (Math.pow(
                marketReturn - comparisonFund,
                65 - Math.max(0, Math.min(age, 65))
            ) -
                Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65))))) /
            (marketReturn - comparisonFund - salaryGrowth) +
            presentValueOfPensionFund *
                Math.pow(
                    marketReturn - comparisonFund,
                    65 - Math.max(0, Math.min(age, 65))
                )
    );
    var tulevaFee = 0.0037;
    var totalSavingWithTuleva = Math.ceil(
        (grossWage *
            0.06 *
            12 *
            (Math.pow(
                marketReturn - tulevaFee,
                65 - Math.max(0, Math.min(age, 65))
            ) -
                Math.pow(salaryGrowth, 65 - Math.max(0, Math.min(age, 65))))) /
            (marketReturn - tulevaFee - salaryGrowth) +
            presentValueOfPensionFund *
                Math.pow(
                    marketReturn - tulevaFee,
                    65 - Math.max(0, Math.min(age, 65))
                ) -
            futureValueOfPensionFund
    );
    var futureValueWithTuleva =
        futureValueOfPensionFund + totalSavingWithTuleva;

    if (window.LANGCODE === "et") {
        comparisonFundPercentage = comparisonFundPercentage.replace(".", ",");
    }

    $("#future-value").text(format(futureValueOfPensionFund) + " €");
    $("#future-value-tuleva").text(format(futureValueWithTuleva) + " €");
    $("#tuleva-saving").text(format(totalSavingWithTuleva) + " €");
    $("#fund-fee").text(comparisonFundPercentage);
};

appendFunds();
calculateSaving();
