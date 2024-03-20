const url1 = 'regions:the-north|people:hodor,the-hound|omg:true';

function parseFilterUrl(url, index=0, filter=[]) {
    const parts = url.split('|');

    if (index >= parts.length) {
        return filter;
    }

    const part = parts[index];
    const splittedPart = part.split(':');

    const obj = {};
    obj[splittedPart[0]] = splittedPart[1].split(',');
    filter.push(obj);

    return parseFilterUrl(url, index + 1, filter);
}

const filters = parseFilterUrl(url1);
console.log({filters});