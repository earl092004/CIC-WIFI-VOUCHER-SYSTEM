const kioskTimeoutMs = 5 * 60 * 1000;

if (window.location.pathname.startsWith('/kiosk/')) {
	window.setTimeout(() => {
		window.location.assign('/kiosk/student');
	}, kioskTimeoutMs);
}
