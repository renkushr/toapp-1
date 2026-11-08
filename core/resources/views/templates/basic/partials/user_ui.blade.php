@push('style')
    <style>
        :root {
            --to-ink: #0b2033;
            --to-muted: #64748b;
            --to-line: rgba(15, 23, 42, .1);
            --to-blue: #1268f3;
            --to-cyan: #13c8d6;
            --to-green: #16a34a;
            --to-orange: #f97316;
            --to-paper: rgba(255, 255, 255, .92);
            --to-shadow: 0 22px 60px rgba(15, 23, 42, .16);
            --to-radius-lg: 30px;
            --to-radius-md: 22px;
        }

        body {
            background: #e8f2f8;
            color: var(--to-ink);
            overflow-x: hidden;
        }

        .main-wrapper,
        .app-page {
            min-height: 100vh;
        }

        .app-page {
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            min-height: 100vh;
            padding: 18px 14px 110px;
            background:
                radial-gradient(circle at 10% -8%, rgba(19, 200, 214, .42), transparent 34%),
                radial-gradient(circle at 95% 0%, rgba(18, 104, 243, .34), transparent 32%),
                linear-gradient(180deg, #f7fbff 0%, #e7f4fb 44%, #f7fbff 100%);
        }

        .app-page::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(18, 104, 243, .045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(18, 104, 243, .045) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(180deg, #000 0%, transparent 65%);
        }

        .app-container {
            position: relative;
            z-index: 1;
            width: min(100%, 1040px);
            margin: 0 auto;
        }

        img,
        svg,
        video {
            max-width: 100%;
        }

        .app-topbar,
        .app-hero,
        .app-card,
        .app-stat,
        .app-list-card,
        .app-plan-card,
        .app-empty-state {
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .app-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .app-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--to-ink);
            font-weight: 600;
            letter-spacing: -.02em;
        }

        .app-brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 14px;
            padding: 5px;
            background: rgba(255, 255, 255, .78);
            box-shadow: 0 14px 28px rgba(15, 23, 42, .1);
        }

        .app-icon-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border: 0;
            border-radius: 16px;
            color: var(--to-ink);
            background: rgba(255, 255, 255, .78);
            box-shadow: 0 14px 32px rgba(15, 23, 42, .12);
        }

        .user-chat-unread-badge,
        .app-icon-btn .user-chat-unread-count {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: #fff !important;
            font-size: 11px;
            font-weight: 700;
            line-height: 20px;
            text-align: center;
            box-shadow: 0 0 0 2px #fff;
        }

        .app-icon-btn.user-chat-notify-target.has-unread {
            color: #ef4444;
        }

        .user-chat-banner-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            margin-left: 8px;
            padding: 0 7px;
            border-radius: 999px;
            background: #fff;
            color: #ef4444;
            font-size: 12px;
            font-weight: 800;
            vertical-align: middle;
        }

        .user-chat-toast-inner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            background: #fff;
            border-radius: 16px;
        }

        .user-chat-toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(18, 104, 243, .12);
            color: #1268f3;
            font-size: 20px;
            flex-shrink: 0;
        }

        .app-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: var(--to-radius-lg);
            padding: 24px;
            color: #fff;
            background:
                radial-gradient(circle at 86% 18%, rgba(255, 255, 255, .28), transparent 22%),
                linear-gradient(135deg, #053d79 0%, #1268f3 52%, #14c7d6 100%);
            box-shadow: var(--to-shadow);
        }

        .app-hero::after {
            content: "";
            position: absolute;
            right: -48px;
            bottom: -70px;
            width: 190px;
            height: 190px;
            border-radius: 999px;
            border: 32px solid rgba(255, 255, 255, .16);
        }

        .app-hero > * {
            position: relative;
            z-index: 1;
        }

        .app-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding: 7px 11px;
            border-radius: 999px;
            color: rgba(255, 255, 255, .9);
            background: rgba(255, 255, 255, .14);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .app-hero h1 {
            margin: 0;
            color: inherit;
            font-size: clamp(28px, 7vw, 48px);
            font-weight: 600;
            line-height: 1.02;
            letter-spacing: -.05em;
        }

        .app-hero p {
            margin: 10px 0 0;
            max-width: 620px;
            color: rgba(255, 255, 255, .82);
            font-size: 15px;
        }

        .app-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .app-chat-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 0 0 16px;
            padding: 16px 18px;
            border: 1px solid rgba(18, 104, 243, .22);
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(18, 104, 243, .14), rgba(19, 200, 214, .12));
            box-shadow: 0 16px 36px rgba(18, 104, 243, .12);
            color: var(--to-ink);
            text-decoration: none;
        }

        .app-chat-banner:hover {
            color: var(--to-ink);
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(18, 104, 243, .16);
        }

        .app-chat-banner-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            flex: 0 0 auto;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(135deg, #1268f3, #13c8d6);
            font-size: 22px;
        }

        .app-chat-banner-body {
            min-width: 0;
            flex: 1 1 auto;
        }

        .app-chat-banner-body strong,
        .app-chat-banner-body span {
            display: block;
        }

        .app-chat-banner-body strong {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .app-chat-banner-body span {
            margin-top: 2px;
            color: var(--to-muted);
            font-size: 13px;
            line-height: 1.35;
        }

        .app-chat-banner-arrow {
            flex: 0 0 auto;
            color: #1268f3;
            font-size: 22px;
        }

        .app-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 0 18px;
            border: 0;
            border-radius: 16px;
            font-weight: 600;
            letter-spacing: -.01em;
            text-align: center;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .app-btn:hover {
            transform: translateY(-1px);
        }

        .app-btn-primary:hover {
            color: #fff;
        }

        .app-btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #1268f3, #13c8d6);
            box-shadow: 0 16px 35px rgba(18, 104, 243, .2);
        }

        .app-hero .app-btn-primary {
            color: #07345f;
            background: #fff;
            box-shadow: 0 16px 35px rgba(3, 24, 46, .18);
        }

        .app-hero .app-btn-primary:hover {
            color: #07345f;
        }

        .app-btn-secondary {
            color: var(--to-ink);
            background: rgba(15, 23, 42, .08);
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, .08);
        }

        .app-btn-secondary:hover {
            color: var(--to-ink);
        }

        .app-hero .app-btn-secondary {
            color: #fff;
            background: rgba(255, 255, 255, .16);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .2);
        }

        .app-hero .app-btn-secondary:hover {
            color: #fff;
        }

        .app-section-title {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin: 26px 0 14px;
        }

        .app-section-title h2 {
            margin: 0;
            color: var(--to-ink);
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -.03em;
        }

        .app-section-title p {
            margin: 4px 0 0;
            color: var(--to-muted);
            font-size: 13px;
        }

        .app-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--to-blue);
            font-weight: 600;
            font-size: 13px;
        }

        .app-grid {
            --bs-gutter-x: 14px;
            --bs-gutter-y: 14px;
        }

        .app-card,
        .app-stat,
        .app-list-card,
        .app-empty-state {
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: var(--to-radius-md);
            background: var(--to-paper);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .08);
        }

        .app-card {
            padding: 18px;
        }

        .app-social-card__email {
            color: #1268f3;
            font-weight: 600;
            text-decoration: none;
            word-break: break-word;
        }

        .app-social-card__email:hover {
            text-decoration: underline;
        }

        .app-social-card__grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .app-social-card__item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 84px;
            padding: 0.75rem 0.4rem;
            border-radius: 16px;
            border: 1px solid rgba(15, 23, 42, .08);
            background: rgba(248, 250, 252, .9);
            color: #0f172a;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: background .15s ease, border-color .15s ease, color .15s ease, transform .15s ease;
        }

        .app-social-card__item:hover {
            background: rgba(18, 104, 243, .08);
            border-color: rgba(18, 104, 243, .22);
            color: #1268f3;
            transform: translateY(-1px);
        }

        .app-social-card__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .08);
        }

        .app-social-card__label {
            line-height: 1.2;
            text-align: center;
        }

        @media (max-width: 575.98px) {
            .app-social-card__grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .app-stat {
            position: relative;
            overflow: hidden;
            padding: 16px;
            min-height: 128px;
        }

        .app-stat::after {
            content: "";
            position: absolute;
            right: -32px;
            bottom: -42px;
            width: 104px;
            height: 104px;
            border-radius: 999px;
            background: var(--tone, rgba(18, 104, 243, .11));
        }

        .app-stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 15px;
            color: var(--tone-color, var(--to-blue));
            background: var(--tone, rgba(18, 104, 243, .11));
            font-size: 22px;
        }

        .app-stat label {
            display: block;
            margin-top: 12px;
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .app-stat strong {
            display: block;
            margin-top: 5px;
            color: var(--to-ink);
            font-size: 23px;
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -.04em;
        }

        .app-list-card {
            overflow: hidden;
        }

        .app-list-card,
        .app-card,
        .app-plan-card,
        .app-table-shell {
            min-width: 0;
        }

        .app-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--to-ink);
            border-bottom: 1px solid var(--to-line);
        }

        .app-list-item:last-child {
            border-bottom: 0;
        }

        .app-list-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 15px;
            color: var(--to-blue);
            background: rgba(18, 104, 243, .1);
            font-size: 22px;
            flex: 0 0 auto;
        }

        .app-list-body {
            min-width: 0;
            flex: 1 1 auto;
        }

        .app-list-body strong,
        .app-list-body span {
            display: block;
        }

        .app-list-body strong {
            color: var(--to-ink);
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .app-list-body span {
            color: var(--to-muted);
            font-size: 12px;
            margin-top: 2px;
        }

        .app-amount {
            font-weight: 600;
            white-space: nowrap;
        }

        .app-amount.is-plus {
            color: var(--to-green);
        }

        .app-amount.is-minus {
            color: #dc2626;
        }

        .app-copy-box {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            margin-top: 14px;
        }

        .app-copy-box input,
        .app-form-control,
        .form--control,
        .form-control {
            width: 100%;
            min-height: 48px;
            padding: 0 16px;
            border: 1px solid rgba(15, 23, 42, .1);
            border-radius: 16px;
            color: var(--to-ink);
            background: rgba(255, 255, 255, .88);
            box-shadow: none;
            font-size: 15px;
            line-height: 1.45;
            letter-spacing: 0.01em;
        }

        .app-copy-box input {
            padding: 0 18px;
            font-size: 15px;
            letter-spacing: 0.03em;
        }

        .app-copy-box input:focus,
        .app-form-control:focus,
        .form--control:focus,
        .form-control:focus {
            border-color: rgba(18, 104, 243, .45);
            box-shadow: 0 0 0 4px rgba(18, 104, 243, .1);
        }

        .app-field-label {
            display: block;
            margin-bottom: 8px;
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .app-form-grid {
            display: grid;
            gap: 14px;
        }

        .app-field {
            display: grid;
            gap: 8px;
            margin: 0;
        }

        .app-field span {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .app-field small {
            color: var(--to-muted);
            font-size: 12px;
        }

        .app-field textarea.form-control {
            min-height: 140px;
            padding: 14px 16px;
            resize: vertical;
        }

        .app-form-split {
            display: grid;
            gap: 14px;
        }

        @media (min-width: 768px) {
            .app-form-split {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .app-support-note {
            margin: 0;
            color: var(--to-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .app-file-uploads {
            display: grid;
            gap: 10px;
        }

        .app-file-upload-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
        }

        .app-file-remove {
            display: inline-grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 14px;
            color: #fff;
            background: #dc2626;
            font-size: 18px;
            flex-shrink: 0;
        }

        .app-page .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.2;
            vertical-align: middle;
        }

        .app-page .badge--success { background: rgba(22, 163, 74, .12); color: #16a34a; }
        .app-page .badge--primary { background: rgba(18, 104, 243, .12); color: #1268f3; }
        .app-page .badge--warning { background: rgba(249, 115, 22, .12); color: #ea580c; }
        .app-page .badge--dark { background: rgba(15, 23, 42, .08); color: #475569; }

        .app-payout-methods {
            display: grid;
            gap: 12px;
        }

        @media (min-width: 768px) {
            .app-payout-methods {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .app-payout-method-card {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 168px;
            padding: 18px;
            border: 1px solid rgba(15, 23, 42, .1);
            border-radius: 22px;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
            cursor: pointer;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .app-payout-method-card:hover {
            transform: translateY(-1px);
        }

        .app-payout-method-card input {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
        }

        .app-payout-method-card:has(input:checked) {
            border-color: rgba(18, 104, 243, .5);
            box-shadow: 0 18px 40px rgba(18, 104, 243, .14);
        }

        .app-payout-method-icon,
        .app-payout-method-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }

        .app-payout-method-icon {
            width: 44px;
            height: 44px;
            color: var(--to-blue);
            background: rgba(18, 104, 243, .1);
            font-size: 22px;
        }

        .app-payout-method-check {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 28px;
            height: 28px;
            color: #fff;
            background: rgba(100, 116, 139, .22);
            opacity: .55;
        }

        .app-payout-method-card:has(input:checked) .app-payout-method-check {
            background: linear-gradient(135deg, #1268f3, #13c8d6);
            opacity: 1;
        }

        .app-payout-method-card strong {
            color: var(--to-ink);
            font-size: 17px;
        }

        .app-payout-method-card span:not(.app-payout-method-icon):not(.app-payout-method-check) {
            color: var(--to-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .app-payout-method-card small {
            margin-top: auto;
            color: var(--to-blue);
            font-size: 12px;
            font-weight: 600;
        }

        .app-payout-panel {
            display: grid;
            gap: 14px;
        }

        .app-password-field {
            position: relative;
        }

        .app-password-field input {
            padding-right: 52px;
        }

        .app-password-field button {
            position: absolute;
            right: 8px;
            bottom: 4px;
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 14px;
            color: var(--to-blue);
            background: rgba(18, 104, 243, .08);
            font-size: 20px;
        }

        .app-method-card {
            height: 100%;
        }

        .app-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .app-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 36px;
            padding: 0 12px;
            border-radius: 999px;
            color: #075985;
            background: rgba(14, 165, 233, .12);
            font-size: 12px;
            font-weight: 600;
        }

        .app-plan-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 100%;
            overflow: hidden;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: 28px;
            background:
                radial-gradient(circle at 100% 0%, rgba(20, 199, 214, .18), transparent 34%),
                linear-gradient(180deg, rgba(255, 255, 255, .96), rgba(255, 255, 255, .78));
            box-shadow: 0 18px 48px rgba(15, 23, 42, .1);
        }

        .app-plan-card.featured {
            border-color: rgba(18, 104, 243, .32);
            box-shadow: 0 24px 58px rgba(18, 104, 243, .2);
        }

        .app-plan-badge {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 30px;
            padding: 0 10px;
            border-radius: 999px;
            color: #075985;
            background: rgba(14, 165, 233, .13);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .app-hero .app-pill {
            color: #fff;
            background: rgba(255, 255, 255, .18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .16);
        }

        .app-hero .app-pill i {
            color: #fff;
        }

        .app-plan-card h3,
        .app-plan-card h4 {
            margin: 16px 0 0;
            color: var(--to-ink);
            font-size: 23px;
            font-weight: 600;
            letter-spacing: -.04em;
        }

        .app-plan-range {
            margin-top: 8px;
            color: var(--to-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .app-plan-return {
            display: flex;
            align-items: end;
            gap: 6px;
            margin-top: 18px;
            color: var(--to-blue);
        }

        .app-plan-return strong {
            font-size: 34px;
            font-weight: 600;
            line-height: 1;
            letter-spacing: -.05em;
        }

        .app-plan-return span {
            margin-bottom: 4px;
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .app-feature-list {
            display: grid;
            gap: 10px;
            padding: 0;
            margin: 18px 0;
            list-style: none;
        }

        .app-feature-list li {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--to-ink);
            font-size: 13px;
            font-weight: 600;
        }

        .app-feature-list i {
            color: var(--to-green);
            font-size: 18px;
        }

        .app-plan-card .app-btn {
            margin-top: auto;
            width: 100%;
            color: #fff;
            background: linear-gradient(135deg, #1268f3, #13c8d6);
            box-shadow: 0 18px 36px rgba(18, 104, 243, .22);
        }

        .app-empty-state {
            padding: 28px;
            text-align: center;
            color: var(--to-muted);
        }

        .app-network-hero {
            background:
                radial-gradient(circle at 18% 15%, rgba(255, 255, 255, .24), transparent 22%),
                radial-gradient(circle at 90% 20%, rgba(20, 199, 214, .42), transparent 30%),
                linear-gradient(135deg, #062b55 0%, #1268f3 48%, #13c8d6 100%);
        }

        .app-network-copy {
            width: min(100%, 720px);
        }

        .app-network-copy input {
            color: #fff;
            border-color: rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .14);
        }

        .app-network-card {
            overflow: hidden;
        }

        .app-network-ring {
            display: grid;
            place-items: center;
            min-height: 210px;
            border-radius: 24px;
            text-align: center;
            background:
                radial-gradient(circle, rgba(18, 104, 243, .14) 0 38%, transparent 39%),
                conic-gradient(from 140deg, rgba(18, 104, 243, .18), rgba(20, 199, 214, .34), rgba(22, 163, 74, .16), rgba(18, 104, 243, .18));
        }

        .app-network-ring > div {
            display: grid;
            place-items: center;
            width: 148px;
            height: 148px;
            padding: 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 20px 50px rgba(15, 23, 42, .12);
        }

        .app-network-ring span,
        .app-network-ring small {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .app-network-ring strong {
            color: var(--to-ink);
            font-size: 42px;
            line-height: .95;
            font-weight: 600;
            letter-spacing: -.05em;
        }

        .app-network-split {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .app-network-split div {
            padding: 14px;
            border-radius: 18px;
            background: rgba(18, 104, 243, .07);
        }

        .app-network-split span,
        .app-network-split strong {
            display: block;
        }

        .app-network-split span {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .app-network-split strong {
            margin-top: 4px;
            color: var(--to-ink);
            font-size: 24px;
            font-weight: 600;
        }

        .app-status-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .app-status-pill.is-active {
            color: #0f6b44;
            background: rgba(22, 163, 74, .12);
        }

        .app-status-pill.is-muted {
            color: var(--to-muted);
            background: rgba(100, 116, 139, .12);
        }

        .app-status-pill.is-pending {
            color: #9a5a00;
            background: rgba(249, 115, 22, .14);
        }

        .app-status-pill.is-level {
            color: #075985;
            background: rgba(20, 199, 214, .14);
        }

        .app-downline-control {
            display: grid;
            grid-template-columns: minmax(180px, 240px) minmax(0, 1fr);
            gap: 14px;
            align-items: end;
        }

        .app-downline-filter {
            display: grid;
            gap: 8px;
        }

        .app-downline-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            align-items: stretch;
        }

        .app-downline-summary div {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: 76px;
            padding: 12px;
            border-radius: 16px;
            background: rgba(18, 104, 243, .07);
        }

        .app-downline-summary span,
        .app-downline-summary strong {
            display: block;
        }

        .app-downline-summary span {
            min-height: 2.4em;
            color: var(--to-muted);
            font-size: 11px;
            font-weight: 600;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .app-downline-summary strong {
            margin-top: auto;
            color: var(--to-ink);
            font-size: clamp(16px, 2.2vw, 20px);
            font-weight: 600;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-pagination nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        .app-pagination .pagination {
            margin: 0;
        }

        .withdraw-summary-box {
            display: grid;
            gap: 10px;
        }

        .withdraw-summary-box > div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(18, 104, 243, .06);
        }

        .withdraw-summary-box span {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .withdraw-summary-box strong {
            color: var(--to-ink);
            font-weight: 600;
            text-align: right;
        }

        .app-table-shell {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: var(--to-radius-md);
            background: var(--to-paper);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .08);
        }

        .custom--table {
            min-width: 720px;
            margin: 0;
            color: var(--to-ink);
        }

        .table-responsive {
            border-radius: inherit;
            -webkit-overflow-scrolling: touch;
        }

        .custom--table thead th {
            border: 0;
            color: var(--to-muted);
            background: rgba(18, 104, 243, .08);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .custom--table tbody td {
            vertical-align: middle;
            border-color: rgba(15, 23, 42, .08);
            font-size: 13px;
        }

        .responsive-filter-card,
        .boxed {
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: var(--to-radius-md);
            background: var(--to-paper);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .08);
        }

        .app-modal .modal-content,
        #planModal .modal-content,
        #detailModal .modal-content,
        .policy-page-modal .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 28px;
            background: #f8fbff;
            box-shadow: 0 28px 80px rgba(2, 6, 23, .28);
        }

        .app-modal .modal-header,
        #planModal .modal-header,
        #detailModal .modal-header,
        .policy-page-modal .modal-header {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, #053d79, #1268f3 58%, #13c8d6);
        }

        .app-modal .modal-title,
        #planModal .modal-title,
        #detailModal .modal-title,
        .policy-page-modal .modal-title {
            color: #fff;
            font-weight: 600;
            letter-spacing: -.03em;
        }

        .app-modal .modal-body,
        #planModal .modal-body,
        #detailModal .modal-body,
        .policy-page-modal .modal-body {
            padding: 22px;
        }

        .footer-menu-block {
            position: relative;
            height: 0;
            pointer-events: none;
        }

        .footer-menu {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 0;
            left: 50%;
            right: auto;
            bottom: 0;
            width: min(100%, 1040px);
            max-width: 100%;
            height: 72px;
            margin: 0;
            margin-left: 0;
            padding: 6px 8px calc(6px + env(safe-area-inset-bottom, 0px));
            border: 1px solid rgba(255, 255, 255, .32);
            border-bottom: 0;
            border-radius: 22px 22px 0 0;
            background: rgba(5, 61, 121, .94);
            box-shadow: 0 -18px 44px rgba(15, 23, 42, .2);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            transform: translateX(-50%);
            list-style: none;
            z-index: 1050;
            pointer-events: auto;
        }

        .footer-menu li {
            flex: 1 1 0;
            min-width: 0;
            text-align: center;
        }

        .footer-menu .link-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            width: 100%;
            height: 100%;
            min-height: 56px;
            padding: 4px 2px;
            color: rgba(255, 255, 255, .82);
            font-size: 10px;
            font-weight: 600;
            line-height: 1.1;
            text-decoration: none;
            opacity: 1;
            white-space: nowrap;
            overflow: hidden;
        }

        .footer-menu .link-menu .icons,
        .footer-menu .link-menu svg.icons {
            --icon-size: 22px;
            width: 22px !important;
            height: 22px !important;
            flex: 0 0 auto;
        }

        .footer-menu .nav-label {
            display: block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .footer-menu .nav-label--short {
            display: none;
        }

        .footer-menu li.active .link-menu,
        .footer-menu li .link-menu:hover {
            color: #fff;
            opacity: 1;
        }

        .footer-menu li.active .link-menu .icons {
            transform: translateY(-2px);
            filter: drop-shadow(0 8px 10px rgba(20, 199, 214, .35));
        }

        .footer-menu-copyright {
            position: fixed;
            left: 0;
            right: 0;
            bottom: calc(72px + env(safe-area-inset-bottom, 0px));
            z-index: 1040;
            margin: 0;
            padding: 0 12px 4px;
            color: rgba(100, 116, 139, .88);
            font-size: 10px;
            text-align: center;
            pointer-events: none;
        }

        body.has-user-footer-nav .app-page {
            padding-bottom: calc(118px + env(safe-area-inset-bottom, 0px));
        }

        /* Installed PWA / APK-like shell */
        html.toapp-standalone body,
        body.toapp-standalone {
            overscroll-behavior-y: contain;
            -webkit-tap-highlight-color: transparent;
            -webkit-user-select: none;
            user-select: none;
        }

        html.toapp-standalone input,
        html.toapp-standalone textarea,
        html.toapp-standalone select,
        body.toapp-standalone input,
        body.toapp-standalone textarea,
        body.toapp-standalone select {
            -webkit-user-select: text;
            user-select: text;
        }

        @media (display-mode: standalone), (display-mode: minimal-ui) {
            .app-page {
                padding-top: calc(14px + env(safe-area-inset-top, 0px));
            }

            .app-topbar {
                top: calc(8px + env(safe-area-inset-top, 0px));
            }

            .footer-menu {
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }
        }

        @media (max-width: 767px) {
            .app-page {
                padding: 14px 12px calc(118px + env(safe-area-inset-bottom, 0px));
            }

            .app-container {
                width: 100%;
            }

            .app-topbar {
                position: sticky;
                top: 8px;
                z-index: 20;
                padding: 8px;
                border: 1px solid rgba(255, 255, 255, .72);
                border-radius: 22px;
                background: rgba(247, 251, 255, .78);
                box-shadow: 0 14px 36px rgba(15, 23, 42, .1);
            }

            .app-brand span {
                max-width: 52vw;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .app-brand img {
                width: 34px;
                height: 34px;
                border-radius: 12px;
            }

            .app-icon-btn {
                width: 42px;
                height: 42px;
                border-radius: 14px;
            }

            .app-hero {
                padding: 21px;
                border-radius: 26px;
            }

            .app-hero h1 {
                font-size: clamp(27px, 10vw, 38px);
                letter-spacing: -.045em;
            }

            .app-hero p {
                font-size: 14px;
                line-height: 1.55;
            }

            .app-hero-actions .app-btn {
                flex: 1 1 100%;
                width: 100%;
            }

            .app-section-title {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }

            .app-section-title h2 {
                font-size: 18px;
            }

            .app-stat {
                min-height: 116px;
                padding: 14px;
            }

            .app-stat strong {
                font-size: 20px;
                overflow-wrap: anywhere;
            }

            .app-card {
                padding: 15px;
            }

            .app-list-item {
                align-items: flex-start;
                gap: 10px;
                padding: 13px;
            }

            .app-list-icon {
                width: 38px;
                height: 38px;
                border-radius: 13px;
                font-size: 20px;
            }

            .app-amount,
            .app-status-pill {
                white-space: normal;
                text-align: right;
            }

            .app-copy-box {
                grid-template-columns: 1fr;
            }

            .app-copy-box .app-icon-btn {
                width: 100%;
            }

            .app-plan-card {
                border-radius: 22px;
                padding: 15px;
            }

            .app-network-ring {
                min-height: 184px;
            }

            .app-downline-control,
            .app-downline-summary {
                grid-template-columns: 1fr;
            }

            .app-modal .modal-dialog,
            #planModal .modal-dialog,
            #detailModal .modal-dialog,
            .policy-page-modal .modal-dialog {
                margin: 12px;
            }

            .app-modal .modal-body,
            #planModal .modal-body,
            #detailModal .modal-body,
            .policy-page-modal .modal-body {
                padding: 16px;
            }

            .footer-menu {
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100%;
                height: auto;
                min-height: 64px;
                padding: 6px 4px calc(8px + env(safe-area-inset-bottom, 0px));
                border-radius: 18px 18px 0 0;
                transform: none;
            }

            .footer-menu .link-menu {
                gap: 3px;
                min-height: 52px;
                padding: 2px 1px;
                font-size: 9px;
                letter-spacing: -.01em;
            }

            .footer-menu .link-menu .icons,
            .footer-menu .link-menu svg.icons {
                --icon-size: 20px;
                width: 20px !important;
                height: 20px !important;
            }

            .footer-menu .nav-label--full {
                display: none;
            }

            .footer-menu .nav-label--short {
                display: block;
            }

            .footer-menu-copyright {
                bottom: calc(64px + env(safe-area-inset-bottom, 0px));
                font-size: 9px;
            }
        }

        @media (max-width: 430px) {
            .app-page {
                padding-left: 10px;
                padding-right: 10px;
            }

            .app-hero,
            .app-card,
            .app-list-card,
            .app-table-shell {
                border-radius: 20px;
            }

            .app-pills {
                flex-direction: column;
            }

            .app-pill {
                width: 100%;
                justify-content: center;
            }

            .app-list-item {
                flex-wrap: wrap;
            }

            .app-list-body {
                flex: 1 1 calc(100% - 50px);
            }

            .app-amount,
            .app-status-pill {
                margin-left: 48px;
            }

            .custom--table {
                min-width: 650px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function() {
            "use strict";

            window.copyTextToClipboard = async function(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    try {
                        await navigator.clipboard.writeText(text);
                        return true;
                    } catch (error) {}
                }

                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.select();
                const copied = document.execCommand('copy');
                document.body.removeChild(textarea);

                return copied;
            };

            window.shareOrCopyLink = async function(link, options) {
                options = options || {};
                const copiedMessage = options.copiedMessage || 'Link copied';

                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: options.title || document.title,
                            text: options.text || '',
                            url: link,
                        });
                        return;
                    } catch (error) {
                        if (error && error.name === 'AbortError') {
                            return;
                        }
                    }
                }

                const copied = await window.copyTextToClipboard(link);
                if (copied) {
                    notify('success', copiedMessage);
                } else {
                    notify('error', 'Could not copy link. Please copy it manually.');
                }
            };

            document.querySelectorAll('[data-copy-value]').forEach(function(button) {
                button.addEventListener('click', async function() {
                    const value = this.dataset.copyValue || '';
                    if (!value) return;

                    const copied = await window.copyTextToClipboard(value);
                    if (copied) {
                        notify('success', this.dataset.copyMessage || 'Copied');
                    } else {
                        notify('error', 'Could not copy. Please copy manually.');
                    }
                });
            });

            document.querySelectorAll('[data-share-link]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const link = this.dataset.shareLink || '';
                    if (!link) return;

                    window.shareOrCopyLink(link, {
                        title: this.dataset.shareTitle || document.title,
                        text: this.dataset.shareText || '',
                        copiedMessage: this.dataset.shareCopiedMessage || 'Referral link copied',
                    });
                });
            });
        })();
    </script>
@endpush
