

f=open('app/Http/routes.php')
routes = f.readlines()

for l in routes:
    words = l.split('->')
    if len(words)>1:
        if words[1].find('(')>0:
            id = words[1].split('(')
            act = id[0]
            op=id[1].split(',')
            if len(op)>0:
                cmd = op[0].split("'")[1]
                print( act, cmd )
