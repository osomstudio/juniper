describe('BlockRestAPI', () => {
  it('passes', () => {
    var homeURL = '';
    var request = '/wp-json/wp/v2/';

    if ( homeURL === '' ) {
      alert('Fill homeURL in this test .cy.js file.');
    }

    cy.request({
      method: 'GET',
      url: homeURL + request,
      body: {name: "test"},
      failOnStatusCode: false
    })
        .then(response => {
          expect(response.status).to.equal(401)
        })

  })
})