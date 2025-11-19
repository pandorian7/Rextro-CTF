from datetime import datetime, timedelta
from keymap import keycode_map
from pathlib import Path
import random
import struct

this = Path(__file__).parent

time = datetime.now()

char = 95
hotkey = 93

delta = timedelta(microseconds=340)


def toint(binary):
    return struct.unpack('<I', binary)[0]


def pack_ts(dt):
    timestamp = int(dt.timestamp() * 1e6)

    high = (timestamp >> 32) & 0xFFFFFFFF
    low = timestamp & 0xFFFFFFFF

    high_bytes = high.to_bytes(4, byteorder='little')
    low_bytes = low.to_bytes(4, byteorder='little')

    return high_bytes + low_bytes


def sign_time(packet, time):

    packet[12:20] = bytearray(pack_ts(time))
    return packet


def next_time():
    global time
    time += delta
    return time


def update_base(base):
    pos = 0
    base = bytearray(base)
    while pos < len(base):
        block_type = base[pos:pos+4]
        if not block_type:
            break
        size = toint(base[pos+4:pos+8])

        if block_type == b'\x06\x00\x00\x00':
            base[pos:pos+size] = sign_time(base[pos:pos+size], next_time())

        pos += size

    return base


with open(this / 'base', 'rb') as file:
    base = file.read()
    base = update_base(base)

with open(this / 'packet', 'rb') as file:
    base_packet = bytearray(file.read())


def sign_key(packet, key=None):
    val, shift = keycode_map[key]
    packet[hotkey] = shift
    packet[char] = val
    return packet


def create_packet(date, key=None):
    packet = base_packet.copy()
    packet = sign_time(packet, date)
    packet = sign_key(packet, key)
    return packet


def next_packet(key):
    packet = create_packet(next_time(), key)
    return packet


def realize_text(text):
    ret = []
    for ch in text:
        # ret += [None for i in range(random.randint(10, 25))]
        ret.append(ch)
    # ret += [None for i in range(random.randint(10, 25))]
    return ret


def create_capture(text):
    global base
    data = base
    text = realize_text(text)
    for ch in text:
        data += next_packet(ch)
    return data


def create_capture_file(text, filename):
    data = create_capture(text)
    with open(filename, 'wb') as file:
        file.write(data)


def get_chalenge_text(noise_text, secret):
    secret = f" {secret} "
    index = random.randint(0, len(noise_text))
    chalenge_text = noise_text[:index] + secret + noise_text[index:]
    return chalenge_text


def create_chalenge_capturefile(noise_text, secret, filename):
    chalenge_text = get_chalenge_text(noise_text, secret)
    create_capture_file(chalenge_text, filename)
