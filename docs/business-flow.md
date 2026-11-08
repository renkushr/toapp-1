# To-App Business Flow

```mermaid
flowchart TB
    subgraph Entry["Onboarding"]
        A[User signs up / logs in] --> B{Has referral code?}
        B -->|Yes| C[Link to referrer]
        B -->|No| D[Standard account]
        C --> E[Email / OTP verification if required]
        D --> E
    end

    subgraph Qualify["Plan matching"]
        E --> F[Complete profile + conditions<br/>age, sex, smoking, health, ZIP, price range]
        F --> G[System matches eligible products]
        G --> H[User selects a plan]
        H --> I[Accept Terms]
    end

    subgraph Engage["Agent engagement"]
        I --> J[Match Agent by ZIP coverage]
        J --> K[User ↔ Agent chat]
        K --> L{Need plan / price changes?}
        L -->|Yes| M[Agent or Admin updates<br/>product / tier / quote]
        M --> K
        L -->|No| N[User may still edit conditions<br/>until the deal is closed]
        N --> K
    end

    subgraph Close["Offline close"]
        K --> O[Finalize plan offline]
        O --> P[Payment outside the platform]
        P --> Q{Outcome}
        Q -->|Sold| R[Agent / Admin confirms]
        Q -->|Not sold| S[Agent / Admin rejects]
        R --> T[Status: Confirmed]
        S --> U[Status: Rejected]
    end

    subgraph Referral["Referral payout"]
        T --> V{Meets referral rules?}
        V -->|Yes| W[Credit referral fee]
        V -->|No| X[No payout]
        W --> Y[User withdraws from wallet]
    end
```

## Short summary

1. Sign up → profile/conditions → match products → select plan → Terms
2. Chat with ZIP-matched Agent → adjust plan/price as needed
3. Pay offline → Confirm / Reject in-app
4. If referral qualifies → fee credited → withdraw from wallet

## Roles

| Role | Responsibility |
|------|----------------|
| User | Sign up, complete conditions, select plan, chat, refer others, withdraw |
| Agent | Serve ZIP territory, chat, adjust quote/product, confirm after offline payment |
| Admin | Manage products/agents/users, edit user plans, confirm/reject, CMS, reports |
