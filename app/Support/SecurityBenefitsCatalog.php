<?php

namespace App\Support;

final class SecurityBenefitsCatalog
{
    public static function data(): array
    {
        return
        [
            'xdrBenefits' => [
                [
                    'category' => 'Endpoint visibility',
                    'title' => 'Understand device activity',
                    'description' => 'Use endpoint telemetry to review device identity, software posture, process and file events, and the activity surrounding an alert.',
                    'value' => 'More context about potentially affected endpoints',
                ],
                [
                    'category' => 'Workload visibility',
                    'title' => 'Include servers, containers, and services',
                    'description' => 'Where supported workload sources are connected, include server, container, and service activity when investigating alerts and affected assets.',
                    'value' => 'Broader context across managed workloads',
                ],
                [
                    'category' => 'Network detection',
                    'title' => 'Follow suspicious communication paths',
                    'description' => 'Use available network-flow, DNS, connection, and gateway telemetry to investigate unusual destinations, beaconing, or movement between systems.',
                    'value' => 'Broader understanding of communication patterns',
                ],
                [
                    'category' => 'Identity context',
                    'title' => 'Connect actions with users and identities',
                    'description' => 'Correlate sign-in, privilege, directory, and application events to help assess account compromise, misuse, and suspicious access.',
                    'value' => 'More informed investigation of account activity',
                ],
                [
                    'category' => 'Cloud visibility',
                    'title' => 'Include cloud and SaaS activity',
                    'description' => 'Where supported data sources are connected, include cloud control-plane, workload, and SaaS events in cross-domain investigations.',
                    'value' => 'More context across connected cloud services',
                ],
                [
                    'category' => 'Email security',
                    'title' => 'Relate messaging threats to other activity',
                    'description' => 'Where email-security telemetry is available, connect suspicious messages, URLs, attachments, and user reports with related identity and endpoint events.',
                    'value' => 'A clearer view of cross-channel incidents',
                ],
                [
                    'category' => 'Correlated detections',
                    'title' => 'Join related alerts into stronger signals',
                    'description' => 'Relate endpoint, network, identity, cloud, and email events by time, asset, account, and indicator instead of reviewing each alert in isolation.',
                    'value' => 'More meaningful incident context for analysts',
                ],
                [
                    'category' => 'SOC triage',
                    'title' => 'Prioritize alerts with shared context',
                    'description' => 'Give analysts related events, asset criticality, vulnerability context, and known indicators to support consistent severity and escalation decisions.',
                    'value' => 'More focused analyst attention',
                ],
                [
                    'category' => 'Incident response',
                    'title' => 'Coordinate containment and recovery',
                    'description' => 'Support a common incident workflow for scoping impact, assigning owners, recording approvals, and coordinating containment and recovery.',
                    'value' => 'Clearer handoffs and repeatable response',
                ],
                [
                    'category' => 'Root-cause analysis',
                    'title' => 'Reconstruct how an incident unfolded',
                    'description' => 'Build a timeline from related alerts and telemetry to trace initial access, execution, persistence, privilege changes, and subsequent activity.',
                    'value' => 'Better-supported remediation decisions',
                ],
                [
                    'category' => 'Attack-surface visibility',
                    'title' => 'Highlight exposed or unmanaged assets',
                    'description' => 'Compare discovered assets and activity with expected inventory to identify potentially exposed, misconfigured, or unmonitored systems.',
                    'value' => 'More targeted exposure reduction',
                ],
                [
                    'category' => 'Insider-threat context',
                    'title' => 'Review unusual activity in context',
                    'description' => 'Correlate access and endpoint events to help authorized teams investigate anomalous activity while respecting policy and privacy controls.',
                    'value' => 'More context for careful, policy-led review',
                ],
                [
                    'category' => 'Ransomware readiness',
                    'title' => 'Connect precursors to response plans',
                    'description' => 'Relate suspicious encryption, privilege escalation, backup access, and lateral movement indicators to support early investigation and rehearsed response.',
                    'value' => 'More prepared incident coordination',
                ],
                [
                    'category' => 'Malware investigation',
                    'title' => 'Trace suspicious files and execution',
                    'description' => 'Combine process, file, endpoint, and network events with validated indicators to investigate suspected malware activity.',
                    'value' => 'A clearer view of suspected infection scope',
                ],
                [
                    'category' => 'Lateral movement',
                    'title' => 'Follow activity between systems',
                    'description' => 'Relate authentication, remote access, and network events across assets to investigate possible movement after an initial compromise.',
                    'value' => 'Better-supported incident scoping',
                ],
                [
                    'category' => 'Vulnerability context',
                    'title' => 'Prioritize alerts using exposure data',
                    'description' => 'Add known asset and vulnerability context to detections to help assess which findings may carry greater operational risk.',
                    'value' => 'More risk-aware triage',
                ],
                [
                    'category' => 'Threat intelligence',
                    'title' => 'Add context to suspicious indicators',
                    'description' => 'Compare observed domains, addresses, files, and behaviors with maintained threat intelligence, while validating source quality and freshness.',
                    'value' => 'Better-informed investigation priorities',
                ],
                [
                    'category' => 'Asset inventory',
                    'title' => 'Relate detections to asset ownership',
                    'description' => 'Use maintained asset identity, role, owner, and criticality data to make alerts more actionable and identify inventory gaps.',
                    'value' => 'Better incident scoping and accountability',
                ],
                [
                    'category' => 'Cases and evidence',
                    'title' => 'Keep investigation notes and evidence together',
                    'description' => 'Maintain incident timelines, linked alerts, analyst notes, and evidence references in a consistent case record with controlled access.',
                    'value' => 'More complete investigations and handoffs',
                ],
                [
                    'category' => 'Automation and remediation',
                    'title' => 'Standardize repeatable response steps',
                    'description' => 'Automate approved enrichment, notifications, and low-risk remediation tasks, while requiring human review for disruptive actions.',
                    'value' => 'Less repetitive work with safer controls',
                ],
                [
                    'category' => 'Continuous improvement',
                    'title' => 'Learn from incidents and tune coverage',
                    'description' => 'Review incident outcomes, response timing, detection gaps, and recurring causes to refine playbooks, data coverage, and detection logic.',
                    'value' => 'More adaptive security operations',
                ],
            ],
            'siemBenefits' => [
                [
                    'category' => 'Collection',
                    'title' => 'Bring security-relevant logs together',
                    'description' => 'Collect agreed event sources from applications, operating systems, identity platforms, networks, and cloud services through managed integrations.',
                    'value' => 'A central foundation for monitoring',
                ],
                [
                    'category' => 'Parsing and normalization',
                    'title' => 'Make events consistent and searchable',
                    'description' => 'Parse source-specific formats into common fields, validate timestamps, and normalize identities and event types for analysis.',
                    'value' => 'More useful cross-source queries',
                ],
                [
                    'category' => 'Retention',
                    'title' => 'Keep event history for defined periods',
                    'description' => 'Set retention tiers and disposal schedules that balance investigation and audit needs with privacy, policy, and storage constraints.',
                    'value' => 'More predictable event availability and lifecycle',
                ],
                [
                    'category' => 'Governance',
                    'title' => 'Set clear data ownership and controls',
                    'description' => 'Define source owners, access permissions, data quality reviews, and approval practices for collected event information.',
                    'value' => 'More accountable and controlled log management',
                ],
                [
                    'category' => 'Correlation',
                    'title' => 'Connect activity across systems',
                    'description' => 'Use rules and time-based relationships to identify sequences spanning users, hosts, applications, and network services.',
                    'value' => 'Patterns that isolated logs can obscure',
                ],
                [
                    'category' => 'Rule management',
                    'title' => 'Maintain detections as controlled content',
                    'description' => 'Version, review, test, document, and retire detection rules with clear ownership and change approval.',
                    'value' => 'More understandable and maintainable alert logic',
                ],
                [
                    'category' => 'Behavioral analytics and UEBA',
                    'title' => 'Spot deviations from expected activity',
                    'description' => 'Analyze event patterns for unusual behavior by user, service, or asset; validate baselines and account for legitimate changes before escalation.',
                    'value' => 'Additional signals for analyst investigation',
                ],
                [
                    'category' => 'Compliance reporting',
                    'title' => 'Support evidence and audit preparation',
                    'description' => 'Create scoped reports from retained event records and documented controls to support internal audits and applicable compliance reviews.',
                    'value' => 'More organized reporting workflows',
                ],
                [
                    'category' => 'Audit trails',
                    'title' => 'Track access and administrative changes',
                    'description' => 'Retain relevant account, configuration, and privileged-action records so authorized reviewers can reconstruct administrative activity.',
                    'value' => 'Improved accountability and traceability',
                ],
                [
                    'category' => 'Search and forensics',
                    'title' => 'Investigate historical event sequences',
                    'description' => 'Search across normalized event history to establish timelines, scope affected identities and assets, and support post-incident analysis.',
                    'value' => 'Faster evidence discovery',
                ],
                [
                    'category' => 'Dashboards and alerts',
                    'title' => 'Make monitoring status visible',
                    'description' => 'Use role-specific dashboards and alert routes to surface priority detections, ingestion health, and monitoring trends.',
                    'value' => 'Clearer operational awareness',
                ],
                [
                    'category' => 'Integrations',
                    'title' => 'Connect existing security and IT tools',
                    'description' => 'Integrate supported identity, endpoint, cloud, ticketing, and threat-intelligence sources with documented ownership and data quality checks.',
                    'value' => 'Less fragmented security context',
                ],
                [
                    'category' => 'Threat intelligence',
                    'title' => 'Enrich events with current threat context',
                    'description' => 'Compare selected event indicators with maintained intelligence sources and preserve source, confidence, and freshness context for analysts.',
                    'value' => 'More informed event triage',
                ],
                [
                    'category' => 'SOAR workflows',
                    'title' => 'Orchestrate consistent alert handling',
                    'description' => 'Route alerts for enrichment, notification, and case creation using approved playbooks, with human authorization for high-impact actions.',
                    'value' => 'More repeatable operations',
                ],
                [
                    'category' => 'False-positive tuning',
                    'title' => 'Improve signal quality over time',
                    'description' => 'Review alert outcomes, tune thresholds and exceptions, and document why a detection changed without hiding meaningful risk.',
                    'value' => 'More analyst focus on actionable alerts',
                ],
                [
                    'category' => 'Governance',
                    'title' => 'Define ownership and controlled access',
                    'description' => 'Set data-source owners, role-based permissions, review practices, and change controls for detection content and sensitive records.',
                    'value' => 'More dependable oversight',
                ],
                [
                    'category' => 'Scalability',
                    'title' => 'Plan capacity as event volumes grow',
                    'description' => 'Estimate ingestion and search demand, measure storage use, and plan processing capacity as sources and retention needs expand.',
                    'value' => 'More predictable growth planning',
                ],
                [
                    'category' => 'High availability',
                    'title' => 'Design for service continuity',
                    'description' => 'Where required, plan redundancy, backups, recovery objectives, and health monitoring for the SIEM components and data sources.',
                    'value' => 'Better continuity when appropriately engineered',
                ],
                [
                    'category' => 'Cost control',
                    'title' => 'Match collection and retention to risk',
                    'description' => 'Measure ingestion volume, prioritize high-value sources, reduce noisy duplication, and review storage and licensing against retention needs.',
                    'value' => 'Better-informed security data spend',
                ],
                [
                    'category' => 'Real-time monitoring',
                    'title' => 'Surface events while response is useful',
                    'description' => 'Monitor incoming events and alert on defined conditions with timing expectations matched to source delays and operational coverage.',
                    'value' => 'More timely detection opportunities',
                ],
                [
                    'category' => 'Operational measurement',
                    'title' => 'Review coverage and service health',
                    'description' => 'Track ingestion gaps, stale sources, alert workload, investigation outcomes, and response handoffs to guide practical improvements.',
                    'value' => 'Clearer monitoring performance',
                ],
            ],
            'comparison' => [
                [
                    'dimension' => 'Primary focus',
                    'xdr' => 'Correlate and respond across connected security layers.',
                    'siem' => 'Collect, normalize, search, and analyze event data broadly.',
                ],
                [
                    'dimension' => 'Typical starting point',
                    'xdr' => 'Cross-domain detections and incident workflows.',
                    'siem' => 'Central log coverage, retention, queries, and alert rules.',
                ],
                [
                    'dimension' => 'Shared foundation',
                    'xdr' => 'Reliable telemetry, identity and asset context, and governed response.',
                    'siem' => 'Reliable telemetry, identity and asset context, and governed response.',
                ],
            ],
            'businessBenefits' => [
                [
                    'title' => 'Reduce uncertainty during incidents',
                    'description' => 'Shared context, clear ownership, and retained evidence can help teams make and explain response decisions.',
                ],
                [
                    'title' => 'Use staff time more deliberately',
                    'description' => 'Prioritized alerts and repeatable workflows can reduce manual correlation and administrative effort.',
                ],
                [
                    'title' => 'Strengthen oversight',
                    'description' => 'Documented controls, access, retention, and audit evidence can support governance and review.',
                ],
                [
                    'title' => 'Improve investment decisions',
                    'description' => 'Coverage, capacity, alert quality, and outcome measures can inform where to improve or simplify security operations.',
                ],
            ],
        ];
    }
}
