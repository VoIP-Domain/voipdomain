#!/usr/bin/env node
/* eslint-disable import/no-extraneous-dependencies */
const path = require('path')

const fs = require('fs-extra')
const mkdirp = require('mkdirp')

const confPath = require.resolve('./jsdoc.conf')
const destination = require(confPath).opts.destination

mkdirp.sync(destination)

module.exports = ({ stdio = 'inherit' } = {}) => {
  process.stdout.write('Docs...')

  fs.removeSync(destination)

  require('child_process').spawnSync('npx', ['jsdoc', '-c', 'jsdoc.conf.js'], {
    stdio,
    cwd: __dirname,
  })

  fs.copySync(path.join(confPath, '../../img'), `${destination}/img`)

  console.log(' done.')
}

if (!module.parent) {
  module.exports()
}
