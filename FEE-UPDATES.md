# Fee update guide — tuleva.ee

Use this guide whenever Tuleva changes a fund's management fee or ongoing charges.

## Nothing to change in this repo

The theme reads every fee figure from onboarding-service's fund list (`/v1/funds`):

- **Management fee** — the rate in force today in onboarding-service's `investment_fee_rate`
  table, so it switches by itself on the row's start date. A management fee change is one
  dated row in that table.
- **Ongoing charges** — `ongoingChargesFigure`, as the fund's key information document
  (KID, põhiteave) in force prints it, with `ongoingChargesPercentDecimals` saying how many
  decimals it prints the percentage with. A KID change is recorded in onboarding-service's
  KID record table, `investment_ocf_publication`, through the agent gateway, and the pages
  switch on the KID's effective date.
- **Fund manager's units** — Tuleva Fondid AS's units in each fund at the last month end,
  from the fund's unit register, shown with that date.

Where the ongoing charges show:

- the four fund pages: the *Ongoing charges* row and the page's structured data;
- the homepage calculator: TUK75's figure, both the one it shows for Tuleva and the one it
  calculates with;
- the TKF100 landing pages (`taiendav-kogumisfond`, `lapsele-kogumine`,
  `osauhingule-kogumine`): TKF100's figure in the text, and the fee on 10 000 euros worked
  out from it;
- the TKF100 landing page's index fund answer: one figure for all Tuleva funds when the
  four are the same, the lowest and the highest when they are not.

`helpers/fund-figures.php` formats the figures for the page's language (`0,28%` / `0.28%`),
the ongoing charges with the KID's number of decimals. While the fund list cannot be read,
the pages show the last figures it returned (one WP option per fund,
`tuleva_fund_figures_last_good_<ISIN>`). Before it has ever returned a figure, the row or
sentence that states it is left out, and the calculator shows `-` and calculates with the
fallback in `js/calculator.js`. The fund list is cached for 10 minutes, so a change shows
within 10 minutes of the date.

The plan behind this is "TODO — Fund page figures from the database", in the private tuleva
repo.

## What stays manual: WordPress Admin

These pages state the ongoing charges in free text. Edit them in WP Admin on the KID's
effective date; the change is live as soon as it is saved.

- `tuleva.ee/kuidas-tuua-pension-tulevasse/` — the page content, in both the Estonian and
  the English section.
- `tuleva.ee/tasud-alla/` — Tuleva's ongoing charges in the page content.
- TKF100 documents page — the page content (the questions and answers) of page 35292
  (`tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/`) and of its English page 36156
  (`tuleva.ee/en/additional-investment-fund-documents/`).
- TKF100 landing page — the Yoast meta description of `tuleva.ee/taiendav-kogumisfond/` and
  of its English page `tuleva.ee/en/additional-investment-fund/`, in the Yoast box on each.

One place in this repo does not read the fund list: the *Fund comparison* page template
(`fund-comparison/content.php`) types the fee of every fund it compares, Tuleva's included,
in its `FEES` table.

## Verification checklist

On the effective date, in a private window or after a hard refresh (Cmd+Shift+R):

- [ ] `tuleva.ee/tuleva-maailma-aktsiate-pensionifond/`,
      `tuleva.ee/tuleva-maailma-volakirjade-pensionifond/` and
      `tuleva.ee/tuleva-iii-samba-pensionifond/` — the fund table shows the new ongoing charges
- [ ] `tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/` and
      `tuleva.ee/en/additional-investment-fund-documents/` — the fund table, and the questions
      and answers
- [ ] `tuleva.ee` homepage calculator, Estonian and `/en/` — Tuleva's fee per year
- [ ] `tuleva.ee/taiendav-kogumisfond/` and `/en/additional-investment-fund/` — the page text
      and the search description
- [ ] `tuleva.ee/lapsele-kogumine/` and `tuleva.ee/osauhingule-kogumine/` — the fee in the
      page text, and the fee on 10 000 euros
- [ ] `tuleva.ee/kuidas-tuua-pension-tulevasse/` — both language sections
- [ ] `tuleva.ee/tasud-alla/` — Tuleva's fee
