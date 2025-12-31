sa_data = bytes.fromhex('4B4A4E54484B544C544343')
ns = []
for c in sa_data:
    ns.append(chr(c ^ 0x7a))
print("".join(ns)) # 104.21.6.99

