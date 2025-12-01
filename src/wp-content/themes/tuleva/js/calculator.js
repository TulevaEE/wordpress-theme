var averageFundFee = 0.79 / 100;
var tulevaFee = 0.0029;

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
    var parsedAge = parseInt($("#age").val());
    var age = isNaN(parsedAge) ? 30 : parsedAge;
    var parsedNetWage = parseInt($("#netWage").val());
    var netWage = isNaN(parsedNetWage) ? 1800 : parsedNetWage;
    var parsedGrossWage = parseInt($("#grossWage").val());
    let incomeTax = 0.78;
    var grossWage = isNaN(parsedNetWage) ? isNaN(parsedGrossWage) ? 2000 : parsedGrossWage : netWage / incomeTax;
    var marketReturn = 1 + Number($("#marketReturn").val()) / 100;
    var parsedContribution = parseInt($('input[name="pillarContribution"]:checked').val());
    var contribution = 0.04 + (isNaN(parsedContribution) ? 0.02 : parsedContribution / 100);

    var salaryGrowth = 1.03;
    var comparisonFund = getFee();
    var comparisonFundPercentage = (comparisonFund * 100).toFixed(2) + "%";
    var pastAverageReturn = 1.039;
    var pastSalaryGrowth = 1.03;
    var maximumContributionYears = new Date().getFullYear() - 2003;
    let startingAge = 21;

    var presentValueOfPensionFund = Math.ceil(
        (((grossWage * contribution * 12) /
                Math.pow(
                    pastSalaryGrowth,
                    Math.min(
                        Math.max(0, Math.min(age, 65)) - startingAge,
                        maximumContributionYears
                    )
                )) *
            (Math.pow(
                    pastAverageReturn,
                    Math.min(
                        Math.max(0, Math.min(age, 65)) - startingAge,
                        maximumContributionYears
                    )
                ) -
                Math.pow(
                    pastSalaryGrowth,
                    Math.min(
                        Math.max(0, Math.min(age, 65)) - startingAge,
                        maximumContributionYears
                    )
                ))) /
        (pastAverageReturn - pastSalaryGrowth)
    );
    var futureValueOfPensionFund = Math.ceil(
        (grossWage *
            contribution *
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
            contribution *
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

    if (comparisonFund > 0.005) {
        $(".more-fees-high").removeClass('d-none');
        $(".more-fees").addClass('d-none');
        $(".same-fees").addClass('d-none');
        $(".less-fees").addClass('d-none');
    } else if (totalSavingWithTuleva > 0) {
        $(".more-fees-high").addClass('d-none');
        $(".more-fees").removeClass('d-none');
        $(".same-fees").addClass('d-none');
        $(".less-fees").addClass('d-none');
    } else if (Math.round(totalSavingWithTuleva) === 0) {
        $(".more-fees-high").addClass('d-none');
        $(".more-fees").addClass('d-none');
        $(".same-fees").removeClass('d-none');
        $(".less-fees").addClass('d-none');
    } else {
        $(".more-fees-high").addClass('d-none');
        $(".more-fees").addClass('d-none');
        $(".same-fees").addClass('d-none');
        $(".less-fees").removeClass('d-none');
    }

    $("#future-value").text(format(futureValueOfPensionFund) + " €");
    $("#future-value-tuleva").text(format(futureValueWithTuleva) + " €");
    $("#tuleva-saving").text(format(totalSavingWithTuleva) + " €");
    $(".tuleva-saving").text(format(Math.abs(totalSavingWithTuleva)) + " €");
    $("#fund-fee").text(comparisonFundPercentage);
};

appendFunds();
calculateSaving();

var initCalculator = function () {
    $('.second-pillar-calculator').each(function () {
        var $calculator = $(this);

        $calculator.find('input').on('change keyup', function () {
            calculateSaving();
        });

        $calculator.find('.form-range').on('input', function () {
            calculateSaving();
        });

        calculateSaving();
    });
};

initCalculator();
