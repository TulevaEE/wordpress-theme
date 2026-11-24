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

## Text typed in WordPress Admin: shortcodes and Yoast variables

Text typed in WP Admin follows the fund list through a shortcode or a Yoast variable
(`helpers/fund-figure-shortcodes.php`), which print the figure exactly as the fund pages do:

- `[tuleva_ongoing_charges fund="TKF100" fallback="0,28%"]` — a fund's ongoing charges
  (`TUK75`, `TUK00`, `TUV100`, `TKF100`; the code in any case). `fund="all"` prints all four:
  one value when they are the same, `0,28%–0,3%` when they are not.
- `[tuleva_management_fee fund="TUK75" fallback="0,16%"]` — a fund's management fee.
- `%%tuleva_ongoing_charges_tkf100%%` (and `_tuk75`, `_tuk00`, `_tuv100`) in a Yoast description.

With no figure ever read, or a fund code the theme does not know, a shortcode prints its
`fallback`, so the sentence never has a gap; always give one. A logged-in editor sees a warning
beside a shortcode with an unknown fund code or no fallback; readers never do. A Yoast variable
has no fallback and is empty before any figure is read. Shortcodes work in a post's or page's
content (its headings included) and in ACF WYSIWYG fields such as a page's `rich_text` and a
question's answer; not in a post title, a menu or a plain-text field such as a question.

Decided in tuleva #1239 (24.11.2026): these places get the shortcode or the variable, and the
communications lead replaces the typed figure in WP Admin once this is deployed:

- `tuleva.ee/kuidas-tuua-pension-tulevasse/` and `tuleva.ee/en/transfer-pension-tuleva/` —
  both fund cards (the page's `rich_text` field).
- `tuleva.ee/iii-sammas-juhend/` — the III pillar fund's fee in the page text.
- `tuleva.ee/tuleva-taiendav-kogumisfond-dokumendid/` — the questions and answers (the
  page's `fund_components`).
- The Yoast description of `tuleva.ee/taiendav-kogumisfond/`, `tuleva.ee/lapsele-kogumine/`,
  `tuleva.ee/osauhingule-kogumine/`, `tuleva.ee/en/additional-investment-fund/` and
  `tuleva.ee/en/child-savings/`.
- The blog posts listed in tuleva #1239: the II pillar funds and III pillar fund overviews
  (Estonian and English), "Seitse küsimust", the child savings guide and the company
  savings guide. The II pillar funds post also takes both management fees through
  `tuleva_management_fee`.

## What stays manual: WordPress Admin

Edit these on the KID's effective date; the change is live as soon as it is saved.

- Posts where Tuleva's figure is part of a comparison or a calculation: the LHV and Robur
  comparison and the Vanguard ETF story. When the fee changes, the whole passage needs
  rereading.
- News and stories, which describe the fee on their own date.
- Any place where a shortcode or variable has not replaced the typed figure yet.

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
