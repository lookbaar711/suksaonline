(async () => {
  const puppeteer = require('puppeteer')

  const browser = await puppeteer.launch({ headless: true })
  const page = await browser.newPage()
  await page.goto('http://localhost:8000/test/report/5e9fc303002fea51e911bcf6')

//   await page.waitForSelector('progress')

  await page.pdf({
    path: './public/courses/report/5e9fc303002fea51e911bcf6.pdf',
    format: 'A4',
    printBackground: true
  })
//   await page._client.send('Page.setDownloadBehavior', {behavior: 'allow', downloadPath: './public/courses/report/5e9fc303002fea51e911bcf6.pdf'});
  await browser.close()
})()
