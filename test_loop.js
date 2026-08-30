
const puppeteer = require("puppeteer");
(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    let reloads = 0;
    page.on("framenavigated", frame => {
        if (frame === page.mainFrame()) {
            reloads++;
            console.log("Navigated to:", frame.url());
        }
    });
    page.on("console", msg => console.log("Console:", msg.text()));
    page.on("pageerror", err => console.log("Error:", err.message));
    page.on("request", req => console.log("Req:", req.url()));
    
    await page.goto("http://localhost:8080/admin/product_management.php");
    await new Promise(r => setTimeout(r, 5000));
    console.log("Total main frame navigations:", reloads);
    await browser.close();
})();

