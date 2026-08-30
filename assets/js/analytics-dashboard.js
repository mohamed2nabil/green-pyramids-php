(() => {
    const endpoint = 'api/analytics.php';
    const rangeSelect = document.getElementById('ga-date-range');
    const refreshButton = document.getElementById('ga-refresh');
    const status = document.getElementById('ga-status');

    if (!rangeSelect || !status) return;

    const formatter = new Intl.NumberFormat();
    const percentFormatter = new Intl.NumberFormat(undefined, {
        minimumFractionDigits: 1,
        maximumFractionDigits: 1
    });
    const chartColors = ['#1A3022', '#D6AC61', '#276E88', '#9A6A2A', '#6E8D73'];
    let trafficChart = null;
    let devicesChart = null;

    const getElement = (id) => document.getElementById(id);

    const setText = (id, value) => {
        const element = getElement(id);
        if (!element) return;
        element.textContent = value;
        element.removeAttribute('data-skeleton');
    };

    const metric = (overview, key) => overview?.[key] ?? {
        current: 0,
        previous: 0,
        difference: 0,
        percentage_change: 0
    };

    const currentValue = (overview, key) => Number(metric(overview, key).current || 0);

    const formatNumber = (value) => formatter.format(Math.round(Number(value) || 0));

    const formatPercent = (value) => `${percentFormatter.format((Number(value) || 0) * 100)}%`;

    const formatDuration = (seconds) => {
        const total = Math.round(Number(seconds) || 0);
        if (total < 60) return `${total}s`;
        return `${Math.floor(total / 60)}m ${total % 60}s`;
    };

    const comparisonText = (entry) => {
        const change = Number(entry?.percentage_change || 0);
        if (!change) return 'No change vs previous period';
        return `${change > 0 ? '+' : ''}${percentFormatter.format(change)}% vs previous period`;
    };

    const comparisonClass = (entry) => {
        const change = Number(entry?.percentage_change || 0);
        if (change > 0) return 'is-positive';
        if (change < 0) return 'is-negative';
        return '';
    };

    const updateMetric = (valueId, noteId, overview, key, formatterFn = formatNumber, customNote = null) => {
        const entry = metric(overview, key);
        setText(valueId, formatterFn(entry.current));
        const note = getElement(noteId);
        if (!note) return;
        note.textContent = customNote || comparisonText(entry);
        note.classList.remove('is-positive', 'is-negative');
        const stateClass = comparisonClass(entry);
        if (stateClass) note.classList.add(stateClass);
    };

    const fetchAnalytics = async (action, range) => {
        const response = await fetch(`${endpoint}?action=${encodeURIComponent(action)}&range=${encodeURIComponent(range)}`, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' }
        });
        const payload = await response.json().catch(() => null);
        if (!response.ok || !payload?.success) {
            throw new Error('analytics_unavailable');
        }
        return payload.data;
    };

    const setLoading = (isLoading) => {
        refreshButton?.classList.toggle('is-loading', isLoading);
        refreshButton?.toggleAttribute('disabled', isLoading);
        status.textContent = isLoading ? 'Loading analytics data' : 'Analytics updated';
        status.classList.remove('is-error');

        document.querySelectorAll('[data-ga-card] .metric-value, #ga-business-sessions').forEach((element) => {
            if (isLoading) element.setAttribute('data-skeleton', 'true');
        });

        ['ga-top-pages', 'ga-traffic-sources', 'ga-geography', 'ga-devices-list'].forEach((id) => {
            const container = getElement(id);
            if (container && isLoading) renderSkeletonRows(container);
        });
    };

    const renderSkeletonRows = (container) => {
        container.replaceChildren();
        for (let index = 0; index < 4; index += 1) {
            const line = document.createElement('div');
            line.className = 'skeleton-line';
            line.style.width = `${index === 0 ? 92 : 68 + index * 7}%`;
            container.appendChild(line);
        }
    };

    const renderInlineState = (container, title, message, isError = false) => {
        container.replaceChildren();
        const wrapper = document.createElement('div');
        wrapper.className = isError ? 'inline-error' : 'inline-empty';
        const strong = document.createElement('strong');
        strong.textContent = title;
        const text = document.createElement('p');
        text.textContent = message;
        wrapper.append(strong, text);
        if (isError) {
            const retry = document.createElement('button');
            retry.type = 'button';
            retry.className = 'retry-button';
            retry.textContent = 'Retry';
            retry.addEventListener('click', loadAnalytics);
            wrapper.appendChild(retry);
        }
        container.appendChild(wrapper);
    };

    const renderTopPages = (rows) => {
        const container = getElement('ga-top-pages');
        if (!container) return;
        container.replaceChildren();

        if (!Array.isArray(rows) || rows.length === 0) {
            renderInlineState(container, 'No page data yet', 'Top pages will appear when visitors view the website.');
            return;
        }

        const head = document.createElement('div');
        head.className = 'data-table-head';
        ['Page', 'Views', 'Users'].forEach((label) => {
            const span = document.createElement('span');
            span.textContent = label;
            head.appendChild(span);
        });
        container.appendChild(head);

        rows.slice(0, 8).forEach((row) => {
            const item = document.createElement('div');
            item.className = 'data-table-row';
            const name = document.createElement('span');
            name.className = 'data-name';
            name.textContent = row.pageTitle || row.pagePath || 'Untitled page';
            name.title = name.textContent;
            const views = document.createElement('span');
            views.className = 'data-value';
            views.textContent = formatNumber(row.screenPageViews);
            const users = document.createElement('span');
            users.className = 'data-users';
            users.textContent = formatNumber(row.activeUsers);
            item.append(name, views, users);
            container.appendChild(item);
        });
    };

    const renderRankedList = (id, rows, getLabel, getValue, emptyTitle, emptyMessage) => {
        const container = getElement(id);
        if (!container) return;
        container.replaceChildren();

        if (!Array.isArray(rows) || rows.length === 0) {
            renderInlineState(container, emptyTitle, emptyMessage);
            return;
        }

        const visibleRows = rows.slice(0, 8);
        const max = Math.max(...visibleRows.map((row) => Number(getValue(row)) || 0), 1);
        const total = visibleRows.reduce((sum, row) => sum + (Number(getValue(row)) || 0), 0);

        visibleRows.forEach((row) => {
            const value = Number(getValue(row)) || 0;
            const percent = total ? Math.round((value / total) * 100) : 0;
            const item = document.createElement('div');
            item.className = 'rank-row';

            const label = document.createElement('div');
            label.className = 'rank-label';
            label.textContent = getLabel(row) || 'Unspecified';
            label.title = label.textContent;

            const amount = document.createElement('div');
            amount.className = 'rank-value';
            amount.textContent = formatNumber(value);

            const track = document.createElement('div');
            track.className = 'rank-track';
            const fill = document.createElement('div');
            fill.className = 'rank-fill';
            fill.style.width = `${Math.max(4, Math.round((value / max) * 100))}%`;
            track.appendChild(fill);

            const meta = document.createElement('div');
            meta.className = 'rank-meta';
            meta.textContent = `${percent}% of listed sessions`;

            item.append(label, amount, track, meta);
            container.appendChild(item);
        });
    };

    const renderTrafficChart = (timeline) => {
        const canvas = getElement('ga-traffic-chart');
        const empty = getElement('ga-traffic-empty');
        if (!canvas || typeof Chart === 'undefined') return;

        if (trafficChart) {
            trafficChart.destroy();
            trafficChart = null;
        }

        const hasData = Array.isArray(timeline) && timeline.length > 0;
        empty.hidden = hasData;
        canvas.hidden = !hasData;

        if (!hasData) return;

        const labels = timeline.map((row) => row.date || '');
        const activeUsers = timeline.map((row) => Number(row.activeUsers) || 0);
        const sessions = timeline.map((row) => Number(row.sessions) || 0);

        trafficChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Active Users',
                        data: activeUsers,
                        borderColor: '#1A3022',
                        backgroundColor: 'rgba(26, 48, 34, 0.10)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 4
                    },
                    {
                        label: 'Sessions',
                        data: sessions,
                        borderColor: '#D6AC61',
                        backgroundColor: 'rgba(214, 172, 97, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#173F35',
                        padding: 12,
                        titleFont: { family: 'Inter', size: 12, weight: '600' },
                        bodyFont: { family: 'Inter', size: 12 },
                        callbacks: {
                            label: (context) => `${context.dataset.label}: ${formatNumber(context.parsed.y)}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#7A877D', maxRotation: 0, autoSkip: true, maxTicksLimit: 7 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#EEF1EE' },
                        ticks: { color: '#7A877D', precision: 0 }
                    }
                }
            }
        });
    };

    const renderDevices = (rows) => {
        const canvas = getElement('ga-devices-chart');
        const empty = getElement('ga-devices-empty');
        const list = getElement('ga-devices-list');
        if (!canvas || !list || typeof Chart === 'undefined') return;

        if (devicesChart) {
            devicesChart.destroy();
            devicesChart = null;
        }

        const hasData = Array.isArray(rows) && rows.length > 0;
        empty.hidden = hasData;
        canvas.hidden = !hasData;
        list.replaceChildren();

        if (!hasData) {
            renderInlineState(list, 'No device data yet', 'Device categories will appear when sessions are recorded.');
            return;
        }

        const visibleRows = rows.slice(0, 5);
        const labels = visibleRows.map((row) => row.deviceCategory || 'Unspecified');
        const values = visibleRows.map((row) => Number(row.sessions) || 0);
        const total = values.reduce((sum, value) => sum + value, 0);

        devicesChart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: chartColors,
                    borderColor: '#FFFFFF',
                    borderWidth: 3,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#173F35',
                        callbacks: {
                            label: (context) => `${context.label}: ${formatNumber(context.parsed)} sessions`
                        }
                    }
                }
            }
        });

        visibleRows.forEach((row, index) => {
            const value = Number(row.sessions) || 0;
            const item = document.createElement('div');
            item.className = 'legend-row';
            const swatch = document.createElement('span');
            swatch.className = 'legend-swatch';
            swatch.style.background = chartColors[index % chartColors.length];
            const label = document.createElement('span');
            label.className = 'legend-label';
            label.textContent = row.deviceCategory || 'Unspecified';
            const amount = document.createElement('strong');
            amount.textContent = `${total ? Math.round((value / total) * 100) : 0}%`;
            item.append(swatch, label, amount);
            list.appendChild(item);
        });
    };

    const renderUnavailable = () => {
        status.textContent = 'Unable to load analytics data right now.';
        status.classList.add('is-error');
        refreshButton?.classList.remove('is-loading');
        refreshButton?.removeAttribute('disabled');

        ['ga-business-sessions', 'ga-active-users', 'ga-total-users', 'ga-new-users', 'ga-sessions', 'ga-page-views', 'ga-engagement-rate', 'ga-average-session-duration', 'ga-engaged-sessions'].forEach((id) => {
            setText(id, '--');
        });

        ['ga-top-pages', 'ga-traffic-sources', 'ga-geography', 'ga-devices-list'].forEach((id) => {
            const container = getElement(id);
            if (container) renderInlineState(container, 'Unable to load analytics data right now.', 'The rest of the dashboard is still available.', true);
        });

        if (trafficChart) trafficChart.destroy();
        if (devicesChart) devicesChart.destroy();
        trafficChart = null;
        devicesChart = null;

        const trafficEmpty = getElement('ga-traffic-empty');
        const devicesEmpty = getElement('ga-devices-empty');
        const trafficCanvas = getElement('ga-traffic-chart');
        const devicesCanvas = getElement('ga-devices-chart');
        if (trafficEmpty) trafficEmpty.hidden = false;
        if (devicesEmpty) devicesEmpty.hidden = false;
        if (trafficCanvas) trafficCanvas.hidden = true;
        if (devicesCanvas) devicesCanvas.hidden = true;
    };

    async function loadAnalytics() {
        const range = rangeSelect.value;
        setLoading(true);

        try {
            const [overview, timeline, pages, sources, geography, devices] = await Promise.all([
                fetchAnalytics('overview', range),
                fetchAnalytics('timeline', range),
                fetchAnalytics('top_pages', range),
                fetchAnalytics('traffic_sources', range),
                fetchAnalytics('geography', range),
                fetchAnalytics('devices', range)
            ]);

            updateMetric('ga-active-users', 'ga-active-users-note', overview, 'activeUsers');
            updateMetric('ga-total-users', 'ga-total-users-note', overview, 'totalUsers');
            updateMetric('ga-new-users', 'ga-new-users-note', overview, 'newUsers');
            updateMetric('ga-sessions', 'ga-sessions-note', overview, 'sessions');
            updateMetric('ga-page-views', 'ga-page-views-note', overview, 'screenPageViews');
            updateMetric('ga-engagement-rate', 'ga-engagement-rate-note', overview, 'engagementRate', formatPercent);
            updateMetric('ga-average-session-duration', 'ga-average-session-duration-note', overview, 'averageEngagementTime', formatDuration, 'Average engagement time');
            updateMetric('ga-engaged-sessions', 'ga-engaged-sessions-note', overview, 'engagedSessions');

            const sessions = metric(overview, 'sessions');
            setText('ga-business-sessions', formatNumber(sessions.current));
            setText('ga-business-sessions-change', comparisonText(sessions).replace(' vs previous period', ''));
            setText('ga-business-sessions-note', 'GA4 sessions for the selected range');

            renderTrafficChart(timeline);
            renderTopPages(pages);
            renderRankedList(
                'ga-traffic-sources',
                sources,
                (row) => row.sessionDefaultChannelGroup || `${row.sessionSource || 'Unknown'} / ${row.sessionMedium || 'none'}`,
                (row) => row.sessions,
                'No source data yet',
                'Acquisition channels will appear when sessions are recorded.'
            );
            renderDevices(devices);
            renderRankedList(
                'ga-geography',
                geography,
                (row) => row.country,
                (row) => row.activeUsers,
                'No geography data yet',
                'Visitor locations will appear when GA4 receives country data.'
            );

            const hasDetailedData = [timeline, pages, sources, geography, devices].some((rows) => Array.isArray(rows) && rows.length > 0);
            status.textContent = hasDetailedData ? 'Analytics updated' : 'No analytics data for this range yet';
            status.classList.toggle('is-error', false);
        } catch (error) {
            renderUnavailable();
        } finally {
            refreshButton?.classList.remove('is-loading');
            refreshButton?.removeAttribute('disabled');
        }
    }

    rangeSelect.addEventListener('change', loadAnalytics);
    refreshButton?.addEventListener('click', loadAnalytics);
    loadAnalytics();
})();
