# Feature Plan: Private GitHub Repository Distribution

This document outlines the strategy for allowing developers to sell and distribute projects hosted in private GitHub repositories through the Mintreu platform.

## 1. Overview

This feature enables developers to list their private GitHub repositories as products. Upon purchase, customers receive a secure download of the repository's contents. This requires secure integration with GitHub's API for authentication and content retrieval.

## 2. Developer Onboarding & GitHub Integration

To access a developer's private repositories, our platform needs explicit authorization.

### 2.1. GitHub Account Linking
-   **Process**: Developers will link their GitHub account to their Mintreu developer profile. This will be initiated from their Filament dashboard.
-   **Method**: We will use **GitHub Apps** for this integration. GitHub Apps offer more granular permissions and are more secure than OAuth Apps for platform-to-GitHub interactions.
-   **Permissions**: The GitHub App will request the minimum necessary permissions:
    -   `Contents: Read-only` access to repositories.
    -   `Metadata: Read-only` access to repository information.
-   **Token Storage**: Upon successful authorization, a **GitHub Installation Token** (or a user-to-server token if using OAuth for simplicity initially) will be securely stored in our database, encrypted. This token will be associated with the developer's Mintreu account.

## 3. Product Registration (GitHub Repository Type)

When a developer creates a new product, they will specify it as a "GitHub Repository" type.

### 3.1. Repository Details
-   **Repository URL**: Developer provides the full URL to their private GitHub repository (e.g., `https://github.com/developer/my-awesome-project`).
-   **Branch/Tag Selection**: Developer selects the specific branch (e.g., `main`, `production`) or a release tag (e.g., `v1.0.0`) that customers should receive upon purchase. This ensures customers always get a stable, intended version.
-   **Product Type Mapping**: The product can be further categorized (e.g., "Nuxt Plugin", "Laravel Package", "Full Application") to guide customers.

## 4. Secure Download & Packaging

When a customer purchases a GitHub repository product, a backend process handles the secure retrieval and packaging.

### 4.1. Triggering the Download
-   **Event**: A successful purchase event triggers a queued job (e.g., `DownloadGitHubRepoJob`).
-   **Authentication**: The job retrieves the developer's encrypted GitHub Access Token from the database.

### 4.2. Repository Content Retrieval
-   **GitHub API**: The job uses the GitHub API to download the repository content. The most straightforward method is to use the "Get a repository archive" endpoint:
    `GET /repos/{owner}/{repo}/zipball/{ref}`
    This endpoint directly provides a ZIP archive of the specified branch or tag.
-   **Processing**:
    -   The downloaded ZIP typically includes the `.git` directory. This should be removed from the archive before distribution to customers.
    -   **Optional: Credential Injection**: If the repository is a template that requires customer-specific credentials (e.g., an API key for our platform), these can be injected into a `.env` file or similar configuration file within the downloaded content, similar to the "Downloadable Starter Kits" feature.
    -   **Optional: License File Injection**: A `LICENSE.md` file with the customer's specific license details could be injected.

### 4.3. Temporary Storage & Customer Download
-   **Storage**: The processed and re-zipped content is stored temporarily in a secure location (e.g., a private S3 bucket, or a local disk with restricted access).
-   **Download Link**: A secure, time-limited download URL is generated and provided to the customer in their dashboard.
-   **Cleanup**: A scheduled task cleans up temporary download files after a set period.

## 5. Security & Best Practices

-   **Token Encryption**: All GitHub Access Tokens stored in our database **must be encrypted**.
-   **Minimal Permissions**: Always request the least privileged access from GitHub.
-   **Rate Limiting**: Be mindful of GitHub API rate limits. Implement retry mechanisms and exponential backoff.
-   **Webhooks for Updates (Future Enhancement)**: Developers could optionally configure a GitHub webhook to notify our platform when a new release or tag is pushed. This could trigger an automatic update of the downloadable product on our platform, ensuring customers always get the latest version.
-   **Audit Logs**: Log all GitHub API interactions for security and debugging.

This feature provides a robust and secure mechanism for developers to monetize their private codebases, significantly enhancing the Mintreu platform's offering.
