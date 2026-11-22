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

These places state the ongoing charges in text typed in WP Admin. Edit them on the KID's
effective date; the change is live as soon as it is saved. Found on 22.11.2026 in the
rendered HTML of every page and in every post's text and Yoast description; which of them
should follow the fund list is being decided in tuleva #1239.

- `tuleva.ee/kuidas-tuua-pension-tulevasse/` and `tuleva.ee/en/transfer-pension-tuleva/` —
  "Tasud 0,28% aastas" / "Fees 0.28% per year" on both fund cards (the page's `rich_text`
  field).
- `tuleva.ee/iii-sammas-juhend/` — the III pillar fund's fee in the page text.
- `tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/` — the questions and answers (the
  page's `fund_components`).
- The Yoast description of `tuleva.ee/taiendav-kogumisfond/`, `tuleva.ee/lapsele-kogumine/`,
  `tuleva.ee/osauhingule-kogumine/`, `tuleva.ee/en/additional-investment-fund/` and
  `tuleva.ee/en/child-savings/`, in the Yoast box on each.
- Blog posts that state the current fee: guides and overviews such as
  `analuusid/tuleva-pensionifondid/` and `oluline/mis-on-tuleva-iii-samba-pensionifondi-sees/`
  and their English versions. tuleva #1239 lists them all. News posts describe the fee on
  their own date and stay as they are.

Two places in this repo type a fee instead of reading the fund list:

- The II pillar rejoin page (`second-pillar-rejoin-content.php`) says "Today you can save
  with an annual fee below 0.3%": a bound, not the figure.
- The *Fund comparison* page template (`fund-comparison/content.php`) types the fee of every
  fund it compares, Tuleva's included, in its `FEES` table. No published page uses the
  template.

## Verification checklist

On the effective date, in a private window or after a hard refresh (Cmd+Shift+R):

- [ ] `tuleva.ee/tuleva-maailma-aktsiate-pensionifond/`,
      `tuleva.ee/tuleva-maailma-volakirjade-pensionifond/` and
      `tuleva.ee/tuleva-iii-samba-pensionifond/` — the fund table shows the new ongoing charges
- [ ] `tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/` and
      `tuleva.ee/en/additional-investment-fund-documents/` — the fund table; on the Estonian
      page also the questions and answers
- [ ] `tuleva.ee` homepage and `tuleva.ee/pensionikalkulaator/` calculators, Estonian and
      English — Tuleva's fee per year
- [ ] `tuleva.ee/taiendav-kogumisfond/`, `tuleva.ee/lapsele-kogumine/`,
      `tuleva.ee/osauhingule-kogumine/` and the English landing pages — the fee in the page
      text, the fee on 10 000 euros, and the search description
- [ ] `tuleva.ee/kuidas-tuua-pension-tulevasse/` and `/en/transfer-pension-tuleva/` — both
      fund cards
- [ ] `tuleva.ee/iii-sammas-juhend/` — the III pillar fund's fee
- [ ] the blog posts ticked in tuleva #1239
