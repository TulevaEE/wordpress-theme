var averageFundFee = 0.86 / 100;
var tulevaFee = 0.0032;

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
    if (typeof calculatorFunds !== 'undefined' && calculatorFunds !== null) {
        $.each(calculatorFunds, function (key, value) {
            $("#comparisonFund").append(
                $("<option></option>")
                    .attr("value", value["fee"])
                    .text(value["name"])
            );
        });
    }
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
    var marketReturn = $("#marketReturn").val();
    var salaryGrowth = 1.03;
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
