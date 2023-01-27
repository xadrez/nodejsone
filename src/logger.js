const Logger = require('events');

const eventEmitter = new Logger();

eventEmitter.on('astring', (e)=>{
	console.log(`${e}`);
});

eventEmitter.emit('astring',{id :1,qty: 5,name: 'George'});