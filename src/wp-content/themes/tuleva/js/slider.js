function calculateSaving(age, netWage) {

    var grossWage = netWage / 0.8;

    var pastAverageReturn = 1.039;
    var pastSalaryGrowth = 1.03;
    var maximumContributionYears = new Date().getFullYear() - 2003;

    var presentValueOfPensionFund = Math.ceil(((grossWage * 0.06 * 12) / (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) * ((Math.pow(pastAverageReturn, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears))) - (Math.pow(pastSalaryGrowth, Math.min(((Math.max(0, Math.min(age, 65))) - 23), maximumContributionYears)))) / (pastAverageReturn - pastSalaryGrowth));

    var averageFundFee = 0.0108;
    var marketReturn = 1.05;
    var salaryGrowth = 1.03;

    var futureValueOfPensionFund = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - averageFundFee, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - averageFundFee - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - averageFundFee, (65 - (Math.max(0, Math.min(age, 65))))))));


    var tulevaFee = 0.0034;

    var totalSavingWithTuleva = Math.ceil((grossWage * 0.06 * 12) * ((Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65)))))) - (Math.pow(salaryGrowth, (65 - (Math.max(0, Math.min(age, 65))))))) / (marketReturn - tulevaFee - salaryGrowth) + (presentValueOfPensionFund * (Math.pow(marketReturn - tulevaFee, (65 - (Math.max(0, Math.min(age, 65))))))) - futureValueOfPensionFund);

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

  onInit: function() {
      this.onSlide(0, 1000);
  },

  onSlide: function(position, wage) {
      $('#wage').html(wage);
      var age = $('#tuleva-calculator-age').val();
      calculateSaving(age, wage);
  },

  onSlideEnd: function(position, value) {}
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

  onInit: function() {
      this.onSlide(0, 30);
  },

  onSlide: function(position, age) {
      $('#age').html(age);
      var wage = $('#tuleva-calculator-wage').val();
      calculateSaving(age, wage);
  },

  onSlideEnd: function(position, value) {}
});

//
// form_structure_1 = [[{
//     "form_identifier": "",
//     "name": "fieldname12",
//     "shortlabel": "",
//     "index": 0,
//     "ftype": "fdiv",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "calcinput",
//     "fields": ["age", "grossWage"],
//     "columns": 1,
//     "title": "div",
//     "fBuild": {}
// }, {
//     "form_identifier": "",
//     "name": "futureValueOfPensionFund",
//     "shortlabel": "",
//     "index": 1,
//     "ftype": "fCalculated",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "Pensionivara pensionile Math.minekul:",
//     "predefined": "",
//     "required": false,
//     "size": "large",
//     "toolbar": "default|mathematical",
//     "eq": "Math.ceil((grossWage*0.06*12)*((Math.pow(1.0414,(65-(Math.max(0, Math.min(age, 65))))))-(Math.pow(1.03,(65-(Math.max(0, Math.min(age, 65)))))))/(0.0114)+(presentValueOfPensionFund*(Math.pow(1.0414,(65-(Math.max(0, Math.min(age, 65))))))))",
//     "suffix": "",
//     "prefix": "\u20ac ",
//     "decimalsymbol": ".",
//     "groupingsymbol": " ",
//     "dependencies": [{"rule": "", "complex": false, "fields": [""]}],
//     "readonly": true,
//     "hidefield": false,
//     "optimizeEq": true,
//     "eq_factored": "(Math.ceil((grossWage*0.06*12)*((Math.pow(1.0414,(65-age)))-(Math.pow(1.03,(65-age))))/(0.0114)+((Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min((age-23),13))))*((Math.pow(1.039,Math.min((age-23),13)))-(Math.pow(1.03,Math.min((age-23),13))))/(0.009)))*(Math.pow(1.0414,(65-age))))))",
//     "dformat": "number",
//     "fBuild": {},
//     "parent": "fieldname11"
// }, {
//     "form_identifier": "",
//     "name": "total_teenustasu",
//     "shortlabel": "",
//     "index": 2,
//     "ftype": "fCalculated",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "Kui teenustasud j\u00e4\u00e4vad t\u00e4nasele tasemele, siis pensionip\u00f5lveks oled maksnud teenustasudeks:",
//     "predefined": "",
//     "required": false,
//     "size": "large",
//     "toolbar": "default|mathematical",
//     "eq": "Math.ceil((grossWage*0.06*12)*((Math.pow(1.054,(65-(Math.max(0, Math.min(age, 65))))))-(Math.pow(1.03,(65-(Math.max(0, Math.min(age, 65)))))))/(0.024)+(presentValueOfPensionFund*(Math.pow(1.054,(65-(Math.max(0, Math.min(age, 65)))))))-futureValueOfPensionFund)",
//     "suffix": "",
//     "prefix": "\u20ac ",
//     "decimalsymbol": ".",
//     "groupingsymbol": " ",
//     "dependencies": [{"rule": "", "complex": false, "fields": [""]}],
//     "readonly": true,
//     "hidefield": false,
//     "optimizeEq": true,
//     "eq_factored": "Math.ceil((grossWage*0.06*12)*((Math.pow(1.054,(65-age)))-(Math.pow(1.03,(65-age))))/(0.024)+(presentValueOfPensionFund*(Math.pow(1.054,(65-age))))-futureValueOfPensionFund)",
//     "dformat": "number",
//     "fBuild": {},
//     "parent": "fieldname11"
// }, {
//     "form_identifier": "",
//     "name": "fieldname18",
//     "shortlabel": "",
//     "index": 3,
//     "ftype": "fCalculated",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "Tulevaga hoiaksid kokku:",
//     "predefined": "",
//     "required": false,
//     "size": "large",
//     "toolbar": "default|mathematical",
//     "eq": "Math.ceil((grossWage*0.06*12)*((Math.pow(1.049,(65-(Math.max(0, Math.min(age, 65))))))-(Math.pow(1.03,(65-(Math.max(0, Math.min(age, 65)))))))/(0.019)+(presentValueOfPensionFund*(Math.pow(1.049,(65-(Math.max(0, Math.min(age, 65)))))))-futureValueOfPensionFund)",
//     "suffix": "",
//     "prefix": "\u20ac ",
//     "decimalsymbol": ".",
//     "groupingsymbol": " ",
//     "dependencies": [{"rule": "", "complex": false, "fields": [""]}],
//     "readonly": true,
//     "hidefield": false,
//     "optimizeEq": true,
//     "eq_factored": "(Math.ceil((grossWage*0.06*12)*((Math.pow(1.049,(65-age)))-(Math.pow(1.03,(65-age))))/(0.019)+(presentValueOfPensionFund*(Math.pow(1.049,(65-age))))-(Math.ceil((grossWage*0.06*12)*((Math.pow(1.0414,(65-age)))-(Math.pow(1.03,(65-age))))/(0.0114)+((Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min((age-23),13))))*((Math.pow(1.039,Math.min((age-23),13)))-(Math.pow(1.03,Math.min((age-23),13))))/(0.009)))*(Math.pow(1.0414,(65-age))))))))",
//     "dformat": "number",
//     "fBuild": {},
//     "parent": "fieldname11"
// }, {
//     "form_identifier": "",
//     "name": "fieldname15",
//     "shortlabel": "",
//     "index": 4,
//     "ftype": "fCalculated",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "T\u00e4naseks makstud teenustasud:",
//     "predefined": "",
//     "required": false,
//     "size": "large",
//     "toolbar": "default|mathematical",
//     "eq": "Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min(((Math.max(0, Math.min(age, 65)))-23),13))))*((Math.pow(1.054,Math.min(((Math.max(0, Math.min(age, 65)))-23),13)))-(Math.pow(1.03,Math.min(((Math.max(0, Math.min(age, 65)))-23),13))))/(0.024)-presentValueOfPensionFund)",
//     "suffix": "",
//     "prefix": "\u20ac ",
//     "decimalsymbol": ".",
//     "groupingsymbol": " ",
//     "dependencies": [{"rule": "", "complex": false, "fields": [""]}],
//     "readonly": true,
//     "hidefield": false,
//     "optimizeEq": true,
//     "eq_factored": "Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min((age-23),13))))*((Math.pow(1.054,Math.min((age-23),13)))-(Math.pow(1.03,Math.min((age-23),13))))/(0.024)-presentValueOfPensionFund)",
//     "dformat": "number",
//     "fBuild": {},
//     "parent": "fieldname11"
// }, {
//     "form_identifier": "",
//     "name": "age",
//     "shortlabel": "",
//     "index": 5,
//     "ftype": "fnumber",
//     "userhelp": "a",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "Sinu age",
//     "predefined": "30",
//     "predefinedClick": false,
//     "required": true,
//     "size": "medium",
//     "thousandSeparator": " ",
//     "decimalSymbol": "",
//     "Math.min": "0",
//     "Math.max": "65",
//     "dformat": "number",
//     "formats": ["digits", "number"],
//     "fBuild": {},
//     "parent": "fieldname12"
// }, {
//     "form_identifier": "",
//     "name": "grossWage",
//     "shortlabel": "",
//     "index": 6,
//     "ftype": "fnumber",
//     "userhelp": "eur",
//     "userhelpTooltip": false,
//     "csslayout": "",
//     "title": "Sinu brutokuupalk",
//     "predefined": "1200",
//     "predefinedClick": false,
//     "required": true,
//     "size": "medium",
//     "thousandSeparator": "  ",
//     "decimalSymbol": ",",
//     "Math.min": "1",
//     "Math.max": "",
//     "dformat": "number",
//     "formats": ["digits", "number"],
//     "fBuild": {},
//     "parent": "fieldname12"
// }, {
//     "form_identifier": "",
//     "name": "fieldname11",
//     "shortlabel": "",
//     "index": 7,
//     "ftype": "fdiv",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "calcresults",
//     "fields": ["presentValueOfPensionFund", "fieldname15", "futureValueOfPensionFund", "total_teenustasu", "fieldname18"],
//     "columns": 1,
//     "title": "div",
//     "fBuild": {}
// }, {
//     "form_identifier": "",
//     "name": "presentValueOfPensionFund",
//     "shortlabel": "",
//     "index": 8,
//     "ftype": "fCalculated",
//     "userhelp": "",
//     "userhelpTooltip": false,
//     "csslayout": "pensionivara",
//     "title": "T\u00e4naseks kogutud  pensionivara:",
//     "predefined": "",
//     "required": false,
//     "size": "large",
//     "toolbar": "default|mathematical",
//     "eq": "Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min(((Math.max(0, Math.min(age, 65)))-23),13))))*((Math.pow(1.039,Math.min(((Math.max(0, Math.min(age, 65)))-23),13)))-(Math.pow(1.03,Math.min(((Math.max(0, Math.min(age, 65)))-23),13))))/(0.009))",
//     "suffix": "",
//     "prefix": "\u20ac ",
//     "decimalsymbol": ".",
//     "groupingsymbol": " ",
//     "dependencies": [{"rule": "", "complex": false, "fields": [""]}],
//     "readonly": true,
//     "hidefield": false,
//     "optimizeEq": true,
//     "eq_factored": "(Math.ceil(((grossWage*0.06*12)/(Math.pow(1.03,Math.min((age-23),13))))*((Math.pow(1.039,Math.min((age-23),13)))-(Math.pow(1.03,Math.min((age-23),13))))/(0.009)))",
//     "dformat": "number",
//     "fBuild": {},
//     "parent": "fieldname11"
// }], {
//     "0": {
//         "title": "Kui palju maksad teenustasusid sina? Arvuta ise!",
//         "description": "",
//         "formlayout": "top_aligned",
//         "formtemplate": "",
//         "evalequations": 1,
//         "autocomplete": 1
//     }, "formid": "cp_calculatedfieldsf_pform_1"
// }];
