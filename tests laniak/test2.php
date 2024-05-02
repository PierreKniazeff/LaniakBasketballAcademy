{
  "id": "105a27cc-0e60-422e-9710-9cb06be507a0",
  "version": "2.0",
  "name": "Laniak",
  "url": "https://levelnext.fr",
  "tests": [{
    "id": "34361f95-c609-40f5-b266-a025677280e1",
    "name": "Test1",
    "commands": [{
      "id": "fddf6c88-b5f9-45b7-bbef-b392ca4a99ce",
      "comment": "",
      "command": "open",
      "target": "/",
      "targets": [],
      "value": ""
    }, {
      "id": "934ed89b-e50f-4dc1-a65a-0dda628469c0",
      "comment": "",
      "command": "setWindowSize",
      "target": "1296x696",
      "targets": [],
      "value": ""
    }, {
      "id": "2f12724b-5a1b-4c79-b368-fc116598454c",
      "comment": "",
      "command": "click",
      "target": "linkText=Connexion",
      "targets": [
        ["linkText=Connexion", "linkText"],
        ["css=.text-link", "css:finder"],
        ["xpath=//a[contains(text(),'Connexion')]", "xpath:link"],
        ["xpath=//div[@id='navbarSupportedContent']/div/a[2]", "xpath:idRelative"],
        ["xpath=//a[contains(@href, 'connexion')]", "xpath:href"],
        ["xpath=//a[2]", "xpath:position"],
        ["xpath=//a[contains(.,'Connexion')]", "xpath:innerText"]
      ],
      "value": ""
    }, {
      "id": "93c0bfd1-ff85-474a-8f90-eb2b08a02258",
      "comment": "",
      "command": "click",
      "target": "id=email",
      "targets": [
        ["id=email", "id"],
        ["name=email", "name"],
        ["css=#email", "css:finder"],
        ["xpath=//input[@id='email']", "xpath:attributes"],
        ["xpath=//input", "xpath:position"]
      ],
      "value": ""
    }, {
      "id": "832989b9-f7be-4fa8-b42c-6dcb55fe5521",
      "comment": "",
      "command": "type",
      "target": "id=email",
      "targets": [
        ["id=email", "id"],
        ["name=email", "name"],
        ["css=#email", "css:finder"],
        ["xpath=//input[@id='email']", "xpath:attributes"],
        ["xpath=//input", "xpath:position"]
      ],
      "value": "kniazeff.pierre@hotmail.fr"
    }, {
      "id": "95050a83-dbed-4bca-9866-06acf25c6ebb",
      "comment": "",
      "command": "click",
      "target": "id=password",
      "targets": [
        ["id=password", "id"],
        ["name=password", "name"],
        ["css=#password", "css:finder"],
        ["xpath=//input[@id='password']", "xpath:attributes"],
        ["xpath=//div[2]/input", "xpath:position"]
      ],
      "value": ""
    }, {
      "id": "c5e121c8-7c84-4407-9ca5-89d7c401c3ec",
      "comment": "",
      "command": "type",
      "target": "id=password",
      "targets": [
        ["id=password", "id"],
        ["name=password", "name"],
        ["css=#password", "css:finder"],
        ["xpath=//input[@id='password']", "xpath:attributes"],
        ["xpath=//div[2]/input", "xpath:position"]
      ],
      "value": "Basket@955"
    }, {
      "id": "9c53ef8b-e1d3-4be1-b87d-8007fa6569e8",
      "comment": "",
      "command": "click",
      "target": "css=.btn-primary",
      "targets": [
        ["css=.btn-primary", "css:finder"],
        ["xpath=//button[@type='submit']", "xpath:attributes"],
        ["xpath=//form/button", "xpath:position"],
        ["xpath=//button[contains(.,'Se connecter')]", "xpath:innerText"]
      ],
      "value": ""
    }]
  }],
  "suites": [{
    "id": "53afd9ad-6874-4b9e-ba82-e87b81d9447a",
    "name": "Default Suite",
    "persistSession": false,
    "parallel": false,
    "timeout": 300,
    "tests": ["34361f95-c609-40f5-b266-a025677280e1"]
  }],
  "urls": ["https://levelnext.fr/"],
  "plugins": []
}